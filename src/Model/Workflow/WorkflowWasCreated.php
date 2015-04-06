<?php
/*
 * This file is part of prooph/link.
 * (c) 2014-2015 prooph software GmbH <contact@prooph.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 * 
 * Date: 4/4/15 - 12:01 AM
 */
namespace Prooph\Link\ProcessManager\Model\Workflow;

use Prooph\EventSourcing\AggregateChanged;
use Prooph\Processing\Processor\NodeName;

/**
 * Event WorkflowWasCreated
 *
 * @package Prooph\Link\ProcessManager\Model\Workflow
 * @author Alexander Miertsch <kontakt@codeliner.ws>
 */
final class WorkflowWasCreated extends AggregateChanged
{
    /**
     * @param NodeName $nodeName
     * @param WorkflowId $workflowId
     * @param $workflowName
     * @return WorkflowWasCreated
     */
    public static function on(NodeName $nodeName, WorkflowId $workflowId, $workflowName)
    {
        return self::occur($workflowId->toString(), [
            'processing_node_name' => $nodeName->toString(),
            'workflow_name' => $workflowName,
        ]);
    }

    /**
     * @return WorkflowId
     */
    public function workflowId()
    {
        return WorkflowId::fromString($this->aggregateId());
    }

    public function processingNodeName()
    {
        return NodeName::fromString($this->payload['processing_node_name']);
    }

    /**
     * @return string
     */
    public function workflowName()
    {
        return $this->payload['workflow_name'];
    }
} 