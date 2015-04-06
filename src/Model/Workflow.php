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
use Prooph\Link\ProcessManager\Model\Workflow\Message;
use Prooph\Link\ProcessManager\Model\Workflow\Process;
use Prooph\Link\ProcessManager\Model\Workflow\WorkflowId;
use Prooph\Link\ProcessManager\Model\Workflow\WorkflowWasCreated;
use Prooph\Processing\Processor\NodeName;

/**
 * Workflow Aggregate
 *
 * The Workflow aggregate is responsible for creating tasks and assigning workflow message handlers to them.
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
     * The process list is indexed by ProcessId.
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

    public function determineFirstTask(Message $startMessage, MessageHandler $firstMessageHandler, ProcessingMetadata $taskMetadata = null)
    {
        if ($this->hasStartMessage()) {
            throw new \RuntimeException(
                sprintf(
                    'Workflow with id %s already has a start message',
                    $this->aggregateId()
                )
            );
        }

        if (is_null($taskMetadata)) {
            $taskMetadata = ProcessingMetadata::noData();
        }


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
}