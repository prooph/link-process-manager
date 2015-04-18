<?php
/*
 * This file is part of prooph/link.
 * (c) 2014-2015 prooph software GmbH <contact@prooph.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 * 
 * Date: 4/16/15 - 6:36 PM
 */
namespace Prooph\Link\ProcessManager\Model\Workflow;
use Prooph\Link\ProcessManager\Command\Workflow\ScheduleFirstTasksForWorkflow;
use Prooph\Link\ProcessManager\Model\MessageHandler\Exception\MessageHandlerNotFound;
use Prooph\Link\ProcessManager\Model\MessageHandler\MessageHandlerCollection;
use Prooph\Link\ProcessManager\Model\Task\TaskCollection;
use Prooph\Link\ProcessManager\Model\Workflow\Exception\WorkflowNotFound;

/**
 * Command Handler ScheduleFirstTasksForWorkflowHandler
 *
 * @package Prooph\Link\ProcessManager\Model\Workflow
 * @author Alexander Miertsch <kontakt@codeliner.ws>
 */
final class ScheduleFirstTasksForWorkflowHandler
{
    /**
     * @var WorkflowCollection
     */
    private $workflowCollection;

    /**
     * @var MessageHandlerCollection
     */
    private $messageHandlerCollection;

    /**
     * @var TaskCollection
     */
    private $taskCollection;

    /**
     * @param WorkflowCollection $workflowCollection
     * @param MessageHandlerCollection $messageHandlerCollection
     * @param TaskCollection $taskCollection
     */
    public function __construct(WorkflowCollection $workflowCollection, MessageHandlerCollection $messageHandlerCollection, TaskCollection $taskCollection)
    {
        $this->workflowCollection = $workflowCollection;
        $this->messageHandlerCollection = $messageHandlerCollection;
        $this->taskCollection = $taskCollection;
    }

    /**
     * @param ScheduleFirstTasksForWorkflow $command
     * @throws Exception\WorkflowNotFound
     * @throws \Prooph\Link\ProcessManager\Model\MessageHandler\Exception\MessageHandlerNotFound
     */
    public function handle(ScheduleFirstTasksForWorkflow $command)
    {
        $workflow = $this->workflowCollection->get($command->workflowId());

        if (is_null($workflow)) {
            throw WorkflowNotFound::withId($command->workflowId());
        }

        $messageHandler = $this->messageHandlerCollection->get($command->firstMessageHandlerId());

        if (is_null($messageHandler)) {
            throw MessageHandlerNotFound::withId($command->firstMessageHandlerId());
        }

        $tasks = $workflow->determineFirstTasks($command->startMessage(), $messageHandler);

        foreach($tasks as $task) {
            $this->taskCollection->add($task);
        }
    }
} 