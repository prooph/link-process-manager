<?php
/*
 * This file is part of prooph/link.
 * (c) 2014-2015 prooph software GmbH <contact@prooph.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 * 
 * Date: 4/5/15 - 8:52 PM
 */
namespace Prooph\Link\ProcessManager\Model\MessageHandler;

use Prooph\EventSourcing\AggregateChanged;
use Prooph\Link\ProcessManager\Model\ProcessingMetadata;
use Prooph\Processing\Processor\NodeName;
use Prooph\Processing\Type\Prototype;

/**
 * Event MessageHandlerWasInstalled
 *
 * @package Prooph\Link\ProcessManager\Model\MessageHandler
 * @author Alexander Miertsch <kontakt@codeliner.ws>
 */
final class MessageHandlerWasInstalled extends AggregateChanged
{
    private $messageHandlerId;

    private $processingNodeName;

    private $handlerType;

    private $dataDirection;

    private $supportedProcessingTypes;

    private $processingMetadata;

    private $preferredProcessingType;

    private $processingId;

    /**
     * @param MessageHandlerId $messageHandlerId
     * @param string $handlerName
     * @param NodeName $processingNodeName
     * @param HandlerType $handlerType
     * @param DataDirection $dataDirection
     * @param ProcessingTypes $processingTypes
     * @param ProcessingMetadata $processingMetadata
     * @param Prototype $preferredProcessingType
     * @param ProcessingId $processingId
     * @return MessageHandlerWasInstalled
     */
    public static function record(
        MessageHandlerId $messageHandlerId,
        $handlerName,
        NodeName $processingNodeName,
        HandlerType $handlerType,
        DataDirection $dataDirection,
        ProcessingTypes $processingTypes,
        ProcessingMetadata $processingMetadata,
        Prototype $preferredProcessingType = null,
        ProcessingId $processingId = null
    ) {
        $event = self::occur(
            $messageHandlerId->toString(),
            [
                'name' => $handlerName,
                'processing_node_name' => $processingNodeName->toString(),
                'handler_type' => $handlerType->toString(),
                'data_direction' => $dataDirection->toString(),
                'supported_processing_types' => $processingTypes->toArray(),
                'processing_metadata' => $processingMetadata->toArray(),
                'preferred_processing_type' => ($preferredProcessingType)? $preferredProcessingType->of() : null,
                'processing_id' => ($processingId)? $processingId->toString() : null,
            ]
        );

        $event->messageHandlerId = $messageHandlerId;
        $event->processingNodeName = $processingNodeName;
        $event->handlerType = $handlerType;
        $event->dataDirection = $dataDirection;
        $event->supportedProcessingTypes = $processingTypes;
        $event->processingMetadata = $processingMetadata;
        $event->preferredProcessingType = $preferredProcessingType;
        $event->processingId = $processingId;

        return $event;
    }

    /**
     * @return MessageHandlerId
     */
    public function messageHandlerId()
    {
        if (is_null($this->messageHandlerId)) {
            $this->messageHandlerId = MessageHandlerId::fromString($this->aggregateId());
        }
        return $this->messageHandlerId;
    }

    /**
     * @return string
     */
    public function messageHandlerName()
    {
        return $this->payload['name'];
    }

    /**
     * @return NodeName
     */
    public function processingNodeName()
    {
        if (is_null($this->processingNodeName)) {
            $this->processingNodeName = NodeName::fromString($this->payload['processing_node_name']);
        }
        return $this->processingNodeName;
    }

    /**
     * @return HandlerType
     */
    public function handlerType()
    {
        if (is_null($this->handlerType)) {
            $this->handlerType = HandlerType::fromString($this->payload['handler_type']);
        }
        return $this->handlerType;
    }

    /**
     * @return DataDirection
     */
    public function dataDirection()
    {
        if (is_null($this->dataDirection)) {
            $this->dataDirection = DataDirection::fromString($this->payload['data_direction']);
        }
        return $this->dataDirection;
    }

    /**
     * @return ProcessingTypes
     */
    public function supportedProcessingTypes()
    {
        if (is_null($this->supportedProcessingTypes)) {
            $this->supportedProcessingTypes = ProcessingTypes::fromArray($this->payload['supported_processing_types']);
        }
        return $this->supportedProcessingTypes;
    }

    /**
     * @return ProcessingMetadata
     */
    public function processingMetadata()
    {
        if (is_null($this->processingMetadata)) {
            $this->processingMetadata = ProcessingMetadata::fromArray($this->payload['processing_metadata']);
        }
        return $this->processingMetadata;
    }

    /**
     * @return null|Prototype
     */
    public function preferredProcessingType()
    {
        $preferredType = $this->payload['preferred_processing_type'];

        if (is_null($preferredType)) return null;

        return $preferredType::prototype();
    }

    /**
     * @return null|ProcessingId
     */
    public function processingId()
    {
        if (is_null($this->payload['processing_id'])) return null;

        return ProcessingId::fromString($this->payload['processing_id']);
    }
} 