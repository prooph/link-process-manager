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

use Prooph\Link\ProcessManager\Model\MessageHandler\DataDirection;
use Prooph\Link\ProcessManager\Model\MessageHandler\HandlerType;
use Prooph\Link\ProcessManager\Model\MessageHandler\MessageHandlerId;
use Prooph\Link\ProcessManager\Model\MessageHandler\ProcessingId;
use Prooph\Link\ProcessManager\Model\MessageHandler\ProcessingTypes;
use Prooph\Link\ProcessManager\Model\Workflow\WorkflowId;
use Prooph\Processing\Processor\NodeName;
use Prooph\Processing\Type\Prototype;

/**
 * Class ProcessingNode
 *
 * The ProcessingNode represents the system which hosts the processing environment.
 * A Workflow can be distributed over different processing nodes. On each node runs an individual workflow processor or
 * a remote workflow message handler. Two processing nodes can communicate via a prooph/service-bus message dispatcher
 * which is configurable for each.
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
     * @param MessageHandlerId $messageHandlerId
     * @param $messageHandlerName
     * @param MessageHandler\HandlerType $handlerType
     * @param MessageHandler\DataDirection $dataDirection
     * @param MessageHandler\ProcessingTypes $supportedProcessingTypes
     * @param ProcessingMetadata $processingMetadata
     * @param null|Prototype $preferredProcessingType
     * @param null|MessageHandler\ProcessingId $processingId
     * @return MessageHandler
     */
    public function installMessageHandler(
        MessageHandlerId $messageHandlerId,
        $messageHandlerName,
        HandlerType $handlerType,
        DataDirection $dataDirection,
        ProcessingTypes $supportedProcessingTypes,
        ProcessingMetadata $processingMetadata,
        Prototype $preferredProcessingType = null,
        ProcessingId $processingId = null
    ) {
        return MessageHandler::fromDefinition(
            $messageHandlerId,
            $messageHandlerName,
            $this->nodeName(),
            $handlerType,
            $dataDirection,
            $supportedProcessingTypes,
            $processingMetadata,
            $preferredProcessingType,
            $processingId
        );
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