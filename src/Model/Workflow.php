<?php
/*
 * This file is part of prooph/link.
 * (c) 2014-2015 prooph software GmbH <contact@prooph.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 * 
 * Date: 4/3/15 - 11:44 PM
 */
namespace Prooph\Link\ProcessManager\Model;

use Assert\Assertion;
use Prooph\EventSourcing\AggregateRoot;
use Prooph\Link\ProcessManager\Model\Task\TaskType;
use Prooph\Link\ProcessManager\Model\Workflow\Exception\MessageIsNotManageable;
use Prooph\Link\ProcessManager\Model\Workflow\Exception\StartTaskIsAlreadyDefined;
use Prooph\Link\ProcessManager\Model\Workflow\Message;
use Prooph\Link\ProcessManager\Model\Workflow\Process;
use Prooph\Link\ProcessManager\Model\Workflow\ProcessId;
use Prooph\Link\ProcessManager\Model\Workflow\ProcessType;
use Prooph\Link\ProcessManager\Model\Workflow\ProcessWasAddedToWorkflow;
use Prooph\Link\ProcessManager\Model\Workflow\StartMessageWasAssignedToWorkflow;
use Prooph\Link\ProcessManager\Model\Workflow\TaskWasAddedToProcess;
use Prooph\Link\ProcessManager\Model\Workflow\WorkflowId;
use Prooph\Link\ProcessManager\Model\Workflow\WorkflowWasCreated;
use Prooph\Processing\Processor\NodeName;
use Prooph\Processing\Type\Description\NativeType;

/**
 * Workflow Aggregate
 *
 * The Workflow aggregate is responsible for creating tasks and assigning workflow message handlers to them.
 * Tasks are organized in processes. The workflow decides if a new task can be added to the task list of an existing
 * process or if a new process is required to handle the task. Reasons for new processes are:
 *
 * 1. Source processing type is a collection but the target message handler can only deal with single items (ForeachProcess)
 * 2. The target message handler is located on another processing node (new sub process is set up)
 * 3. Processing metadata indicates that data should be processed in chunks (ChunkProcess)
 * 4. Same task should be handled by different message handlers in parallel (ForkProcess)
 *
 * Whenever possible the workflow tries to use a LinearMessagingProcess because this is the simplest process.
 *
 * @package Prooph\Link\ProcessManager\Model\Workflow
 * @author Alexander Miertsch <alexander.miertsch.extern@sixt.com>
 */
final class Workflow extends AggregateRoot
{
    /**
     * @var NodeName
     */
    private $processingNodeName;

    /**
     * @var WorkflowId
     */
    private $workflowId;

    /**
     * @var string
     */
    private $name;

    /**
     * @var Message
     */
    private $startMessage;

    /**
     * Internal list of processes which are required to fulfill the workflow.
     * Each process keeps a task list of tasks which are performed by the process and belong to the workflow.
     * The order of the processes defines the execution order of the linked tasks in the processing system.
     *
     * @var Process[]
     */
    private $processList = [];

    /**
     * @param NodeName $nodeName
     * @param WorkflowId $workflowId
     * @param string $workflowName
     * @return Workflow
     */
    public static function locatedOn(NodeName $nodeName, WorkflowId $workflowId, $workflowName)
    {
        Assertion::string($workflowName);
        Assertion::notEmpty($workflowName);

        $instance = new self();

        $instance->recordThat(WorkflowWasCreated::on($nodeName, $workflowId, $workflowName));

        return $instance;
    }

    /**
     * Determine the required task(s) to handle the initial workflow message.
     *
     * In most cases only one task is required, but if the message handler is located on another processing node,
     * the workflow needs to set up two tasks. One for triggering a sub process on the remote processing node and
     * one that is assigned to the sub process and actually triggers the processing of the message on the remote node.
     *
     * @param Message $startMessage
     * @param MessageHandler $firstMessageHandler
     * @param ProcessingMetadata $taskMetadata
     * @return Task[]
     * @throws \RuntimeException
     * @throws Workflow\Exception\StartTaskIsAlreadyDefined
     */
    public function determineFirstTasks(Message $startMessage, MessageHandler $firstMessageHandler, ProcessingMetadata $taskMetadata = null)
    {
        if ($this->hasStartMessage()) {
            throw StartTaskIsAlreadyDefined::forWorkflow($this);
        }

        //We have some kind of chicken egg problem here. We don't know the first task yet, but need to ask
        //the message handler if it can handle the start message. As long as the start message is a command
        //everything is fine, but when the workflow is triggered by an event it will be converted to a command by
        //the first task. To emulate this behaviour we need to convert the event to a command, so that the message handler
        //can perform its check based on the correct message type.
        if ($startMessage->messageType()->isEvent()) {
            $nextMessage = $startMessage->toProcessDataMessage();
        } else {
            $nextMessage = $startMessage;
        }

        if (is_null($taskMetadata)) {
            $taskMetadata = ProcessingMetadata::noData();
        } else {
            $nextMessage = $this->mergeTaskMetadataIntoMessageMetadata($taskMetadata, $nextMessage);
        }

        $processType = $this->determineProcessType($nextMessage, $firstMessageHandler);

        $this->recordThat(StartMessageWasAssignedToWorkflow::record($startMessage, $this));

        $process = Process::ofType($processType, ProcessId::generate());

        $this->recordThat(ProcessWasAddedToWorkflow::record($process, $this));

        $tasks = [];

        //@TODO: Implement sub process set up
        if (! $this->processingNodeName()->equals($firstMessageHandler->processingNodeName())) {
            throw new \RuntimeException("Running message handler on different nodes is not supported yet!");
        }

        //NOTE: To schedule the first task correctly we use the original start message, not the emulated next command
        $task = $this->scheduleTaskFor($firstMessageHandler, $taskMetadata, $startMessage);

        $this->recordThat(TaskWasAddedToProcess::record($this->workflowId(), $process, $task));

        $tasks[] = $task;

        return $tasks;
    }

    /**
     * @return NodeName
     */
    public function processingNodeName()
    {
        return $this->processingNodeName;
    }

    /**
     * @return WorkflowId
     */
    public function workflowId()
    {
        return $this->workflowId;
    }

    /**
     * @return string
     */
    public function name()
    {
        return $this->name;
    }

    /**
     * @return bool
     */
    private function hasStartMessage()
    {
        return ! is_null($this->startMessage);
    }

    /**
     * Checks if the message handler can handle a workflow message with the item processing type of a collection.
     *
     * @param Message $message
     * @param MessageHandler $messageHandler
     * @return bool
     */
    private function isForeachProcessPossible(Message $message, MessageHandler $messageHandler)
    {
        if ($message->processingType()->typeDescription()->nativeType() !== NativeType::COLLECTION) {
            return false;
        }

        $itemMessage = Message::emulateProcessingWorkflowMessage(
            $message->messageType(),
            $message->processingType()->typeProperties()['item']->typePrototype(),
            $message->processingMetadata()
        );

        return $messageHandler->canHandleMessage($itemMessage);
    }

    /**
     * Returns a new message VO with task metadata merged into message metadata
     *
     * @param ProcessingMetadata $taskMetadata
     * @param Message $message
     * @return Message
     */
    private function mergeTaskMetadataIntoMessageMetadata(ProcessingMetadata $taskMetadata, Message $message)
    {
        return Message::emulateProcessingWorkflowMessage(
            $message->messageType(),
            $message->processingType(),
            $message->processingMetadata()->merge($taskMetadata)
        );
    }

    /**
     * Determine the required process type based on given message and the message handler which will receive the message.
     *
     * @param Message $message
     * @param MessageHandler $messageHandler
     * @return ProcessType
     * @throws Workflow\Exception\MessageIsNotManageable
     */
    private function determineProcessType(Message $message, MessageHandler $messageHandler)
    {
        if ($messageHandler->canHandleMessage($message)) {
            if ($message->processingMetadata()->shouldCollectionBeSplitIntoChunks()) {
                $processType = ProcessType::parallelChunk();
            } else {
                $processType = ProcessType::linearMessaging();
            }
        } else {
            //We create the error before trying the foreach alternative to provide the client with the original error message
            //if the alternative fails too.
            $originalError = MessageIsNotManageable::byMessageHandler($messageHandler, $message);

            //Check if we can use a foreach process to avoid the rejection of the message
            if ($this->isForeachProcessPossible($message, $messageHandler)) {
                $processType = ProcessType::parallelForeach();
            } else {
                throw $originalError;
            }
        }

        return $processType;
    }

    /**
     * Creates a new task for given message handler
     *
     * @param MessageHandler $messageHandler
     * @param ProcessingMetadata $taskMetadata
     * @param Workflow\Message $previousMessage
     * @return \Prooph\Link\ProcessManager\Model\Task
     * @throws \RuntimeException
     */
    private function scheduleTaskFor(MessageHandler $messageHandler, ProcessingMetadata $taskMetadata, Message $previousMessage)
    {
        if ($previousMessage->messageType()->isCollectDataMessage()) {
            $taskType = TaskType::collectData();
        } elseif ($previousMessage->messageType()->isDataCollectedMessage() || $previousMessage->messageType()->isDataProcessedMessage()) {
            $taskType = TaskType::processData();
        } else {
            throw new \RuntimeException(
                sprintf(
                    "Failed to determine a task type which can handle a %s message",
                    $previousMessage->messageType()->toString()
                )
            );
        }

        return Task::setUp($messageHandler, $taskType, $taskMetadata);
    }

    /**
     * @return string representation of the unique identifier of the aggregate root
     */
    protected function aggregateId()
    {
        return $this->workflowId->toString();
    }

    /**
     * @param WorkflowWasCreated $event
     */
    protected function whenWorkflowWasCreated(WorkflowWasCreated $event)
    {
        $this->processingNodeName = $event->processingNodeName();
        $this->workflowId = $event->workflowId();
        $this->name = $event->workflowName();
    }

    /**
     * @param StartMessageWasAssignedToWorkflow $event
     */
    protected function whenStartMessageWasAssignedToWorkflow(StartMessageWasAssignedToWorkflow $event)
    {
        $this->startMessage = $event->startMessage();
    }

    /**
     * @param ProcessWasAddedToWorkflow $event
     */
    protected function whenProcessWasAddedToWorkflow(ProcessWasAddedToWorkflow $event)
    {
        $this->processList[] = Process::withTaskList($event->tasks(), $event->processId(), $event->processType());
    }

    /**
     * @param TaskWasAddedToProcess $event
     */
    protected function whenTaskWasAddedToProcess(TaskWasAddedToProcess $event)
    {
        foreach ($this->processList as $process) {
            if ($process->id()->equals($event->processId())) {
                $process->addTask($event->taskId());
                return;
            }
        }
    }
}