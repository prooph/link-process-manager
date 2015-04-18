<?php
/*
 * This file is part of prooph/link.
 * (c) 2014-2015 prooph software GmbH <contact@prooph.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 * 
 * Date: 4/18/15 - 7:27 PM
 */
namespace Prooph\Link\ProcessManager\Model\Workflow;

use Prooph\Link\ProcessManager\Command\Workflow\ScheduleNextTasksForWorkflow;
use Prooph\Link\ProcessManager\Model\MessageHandler\Exception\MessageHandlerNotFound;
use Prooph\Link\ProcessManager\Model\MessageHandler\MessageHandlerCollection;
use Prooph\Link\ProcessManager\Model\Task\Exception\TaskNotFound;
use Prooph\Link\ProcessManager\Model\Task\TaskCollection;
use Prooph\Link\ProcessManager\Model\Workflow\Exception\WorkflowNotFound;

/**
 * Command Handler ScheduleNextTasksForWorkflowHandler
 *
 * @package Prooph\Link\ProcessManager\Model\Workflow
 * @author Alexander Miertsch <kontakt@codeliner.ws>
 */
final class ScheduleNextTasksForWorkflowHandler 
{
    /**
     * @var TaskCollection
     */
    private $taskCollection;

    /**
     * @var WorkflowCollection
     */
    private $workflowCollection;

    /**
     * @var MessageHandlerCollection
     */
    private $messageHandlerCollection;

    /**
     * @param WorkflowCollection $workflowCollection
     * @param TaskCollection $taskCollection
     * @param MessageHandlerCollection $messageHandlerCollection
     */
    public function __construct(WorkflowCollection $workflowCollection, TaskCollection $taskCollection, MessageHandlerCollection $messageHandlerCollection)
    {
        $this->workflowCollection = $workflowCollection;
        $this->taskCollection = $taskCollection;
        $this->messageHandlerCollection = $messageHandlerCollection;
    }

    public function handle(ScheduleNextTasksForWorkflow $command)
    {
        $workflow = $this->workflowCollection->get($command->workflowId());

        if (is_null($workflow)) {
            throw WorkflowNotFound::withId($command->workflowId());
        }

        $previousTask = $this->taskCollection->get($command->previousTaskId());

        if (is_null($previousTask)) {
            throw TaskNotFound::withId($command->previousTaskId());
        }

        $previousMessageHandler = $this->messageHandlerCollection->get($previousTask->messageHandlerId());

        if (is_null($previousMessageHandler)) {
            throw MessageHandlerNotFound::withId($previousTask->messageHandlerId());
        }

        $nextMessageHandler = $this->messageHandlerCollection->get($command->nextMessageHandlerId());

        if (is_null($nextMessageHandler)) {
            throw MessageHandlerNotFound::withId($command->nextMessageHandlerId());
        }

        $nextTasks = $workflow->determineNextTasks($previousTask, $previousMessageHandler, $nextMessageHandler);

        foreach ($nextTasks as $nextTask) {
            $this->taskCollection->add($nextTask);
        }
    }
} 