<?php
/*
 * This file is part of prooph/link.
 * (c) 2014-2015 prooph software GmbH <contact@prooph.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 * 
 * Date: 4/6/15 - 11:41 PM
 */
namespace Prooph\Link\ProcessManager\Model\Workflow;

use Prooph\EventSourcing\AggregateChanged;
use Prooph\Link\ProcessManager\Model\Task\TaskId;
use Prooph\Link\ProcessManager\Model\Workflow;

/**
 * Event ProcessWasAddedToWorkflow
 *
 * @package Prooph\Link\ProcessManager\Model\Workflow
 * @author Alexander Miertsch <kontakt@codeliner.ws>
 */
final class ProcessWasAddedToWorkflow extends AggregateChanged
{
    private $workflowId;

    private $processId;

    private $processType;

    private $tasks;

    /**
     * @param Process $process
     * @param Workflow $workflow
     * @return ProcessWasAddedToWorkflow
     */
    public static function record(Process $process, Workflow $workflow)
    {
        $event = self::occur($workflow->workflowId()->toString(), [
            'process_id' => $process->id()->toString(),
            'process_type' => $process->type()->toString(),
            'task_list' => array_map(function (TaskId $taskId) {$taskId->toString();}, $process->tasks()),
        ]);

        $event->workflowId = $workflow->workflowId();
        $event->processId  = $process->id();
        $event->processType = $process->type();
        $event->tasks = $process->tasks();

        return $event;
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
     * @return ProcessType
     */
    public function processType()
    {
        if (is_null($this->processType)) {
            $this->processType = ProcessType::fromString($this->payload['process_type']);
        }
        return $this->processType;
    }

    /**
     * @return TaskId[]
     */
    public function tasks()
    {
        if (is_null($this->tasks)) {
            $this->tasks = array_map(function($taskId) {return TaskId::fromString($taskId);}, $this->payload['task_list']);
        }
        return $this->tasks;
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
} 