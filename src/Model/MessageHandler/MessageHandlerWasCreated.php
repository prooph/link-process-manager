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
 * Event MessageHandlerWasCreated
 *
 * @package Prooph\Link\ProcessManager\Model\MessageHandler
 * @author Alexander Miertsch <kontakt@codeliner.ws>
 */
final class MessageHandlerWasCreated extends AggregateChanged
{
    /**
     * @return MessageHandlerId
     */
    public function messageHandlerId()
    {
        return MessageHandlerId::fromString($this->aggregateId());
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
        return NodeName::fromString($this->payload['processing_node_name']);
    }

    /**
     * @return HandlerType
     */
    public function handlerType()
    {
        return HandlerType::fromString($this->payload['handler_type']);
    }

    /**
     * @return DataDirection
     */
    public function dataDirection()
    {
        return DataDirection::fromString($this->payload['data_direction']);
    }

    /**
     * @return ProcessingTypes
     */
    public function supportedProcessingTypes()
    {
        return ProcessingTypes::fromArray($this->payload['supported_processing_types']);
    }

    /**
     * @return ProcessingMetadata
     */
    public function processingMetadata()
    {
        return ProcessingMetadata::fromArray($this->payload['processing_metadata']);
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