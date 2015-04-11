<?php
/*
 * This file is part of prooph/link.
 * (c) 2014-2015 prooph software GmbH <contact@prooph.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 * 
 * Date: 4/11/15 - 6:13 PM
 */
namespace Prooph\Link\ProcessManager\Model\Workflow;

use Prooph\EventSourcing\AggregateChanged;
use Prooph\Link\ProcessManager\Model\Task;

/**
 * Event TaskWasAddedToProcess
 *
 * @package Prooph\Link\ProcessManager\Model\Workflow
 * @author Alexander Miertsch <kontakt@codeliner.ws>
 */
final class TaskWasAddedToProcess extends AggregateChanged
{
    private $workflowId;

    private $processId;

    private $taskId;

    /**
     * @param WorkflowId $workflowId
     * @param Process $process
     * @param Task $task
     * @return TaskWasAddedToProcess
     */
    public static function record(WorkflowId $workflowId, Process $process, Task $task)
    {
        $event = self::occur($workflowId->toString(), [
            'process_id' => $process->id()->toString(),
            'task_id' => $task->id()->toString()
        ]);

        $event->workflowId = $workflowId;
        $event->processId  = $process->id();
        $event->taskId     = $task->id();

        return $event;
    }

    /**
     * @return WorkflowId
     */
    public function workflowId()
    {
        if (is_null($this->workflowId)) {
            $this->workflowId = WorkflowId::fromString($this->aggregateId());
        }
        return $this->workflowId;
    }

    /**
     * @return ProcessId
     */
    public function processId()
    {
        if (is_null($this->processId)) {
            $this->processId = ProcessId::fromString($this->payload['process_id']);
        }
        return $this->processId;
    }

    /**
     * @return Task\TaskId
     */
    public function taskId()
    {
        if (is_null($this->taskId)) {
            $this->taskId = Task\TaskId::fromString($this->payload['task_id']);
        }
        return $this->taskId;
    }
} 