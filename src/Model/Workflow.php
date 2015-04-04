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

use Prooph\EventSourcing\AggregateRoot;
use Prooph\Link\ProcessManager\Model\Workflow\WorkflowId;
use Prooph\Link\ProcessManager\Model\Workflow\WorkflowWasCreated;

/**
 * Workflow Aggregate
 *
 * The Workflow Aggregate is responsible for creating tasks and assigning workflow message handlers to them.
 *
 * @package Prooph\Link\ProcessManager\Model\Workflow
 * @author Alexander Miertsch <alexander.miertsch.extern@sixt.com>
 */
final class Workflow extends AggregateRoot
{
    /**
     * @var WorkflowId
     */
    private $workflowId;

    /**
     * @var string
     */
    private $name;

    /**
     * @param WorkflowId $workflowId
     * @param string $name
     * @return Workflow
     */
    public static function createWithName(WorkflowId $workflowId, $name)
    {
        $instance = new self();

        $instance->recordThat(WorkflowWasCreated::occur($workflowId->toString(), ['name' => $name]));

        return $instance;
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
        $this->workflowId = $event->workflowId();
        $this->name = $event->workflowName();
    }
}