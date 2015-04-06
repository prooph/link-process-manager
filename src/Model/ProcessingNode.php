<?php
/*
 * This file is part of prooph/link.
 * (c) 2014-2015 prooph software GmbH <contact@prooph.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 * 
 * Date: 4/5/15 - 11:16 PM
 */
namespace Prooph\Link\ProcessManager\Model;

use Prooph\Link\ProcessManager\Model\Workflow\WorkflowId;
use Prooph\Processing\Processor\NodeName;

/**
 * Class ProcessingNode
 *
 * The ProcessingNode represents the system which hosts the processing environment.
 * A Workflow can be distributed over different processing nodes. On each node runs an individual workflow processor or
 * a remote workflow message handler. Two processing nodes can communicate via a prooph/service-bus message dispatcher
 * which is configurable for each processing node.
 *
 * @package Prooph\Link\ProcessManager\Model
 * @author Alexander Miertsch <kontakt@codeliner.ws>
 */
final class ProcessingNode 
{
    /**
     * @var NodeName
     */
    private $processingNodeName;

    /**
     * @param NodeName $name
     * @return ProcessingNode
     */
    public static function initializeAs(NodeName $name)
    {
        return new self($name);
    }

    /**
     * @param NodeName $name
     */
    private function __construct(NodeName $name)
    {
        $this->processingNodeName = $name;
    }

    /**
     * @return NodeName
     */
    public function nodeName()
    {
        return $this->processingNodeName;
    }

    /**
     * @param WorkflowId $workflowId
     * @param string $workflowName
     * @return Workflow
     */
    public function setUpNewWorkflow(WorkflowId $workflowId, $workflowName)
    {
        return Workflow::locatedOn($this->nodeName(), $workflowId, $workflowName);
    }

    /**
     * @param ProcessingNode $other
     * @return bool
     */
    public function sameNodeAs(ProcessingNode $other)
    {
        return $this->nodeName()->equals($other->nodeName());
    }
} 