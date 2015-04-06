<?php
/*
 * This file is part of prooph/link.
 * (c) 2014-2015 prooph software GmbH <contact@prooph.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 * 
 * Date: 4/5/15 - 1:22 AM
 */
namespace Prooph\Link\ProcessManager\Model;

use Assert\Assertion;
use Prooph\EventSourcing\AggregateRoot;
use Prooph\Link\ProcessManager\Model\MessageHandler\DataDirection;
use Prooph\Link\ProcessManager\Model\MessageHandler\HandlerType;
use Prooph\Link\ProcessManager\Model\MessageHandler\MessageHandlerId;
use Prooph\Link\ProcessManager\Model\MessageHandler\MessageHandlerWasCreated;
use Prooph\Link\ProcessManager\Model\MessageHandler\ProcessingId;
use Prooph\Link\ProcessManager\Model\MessageHandler\ProcessingTypes;
use Prooph\Link\ProcessManager\Model\Task\TaskId;
use Prooph\Link\ProcessManager\Model\Workflow\Message;
use Prooph\Link\ProcessManager\Model\Workflow\MessageType;
use Prooph\Processing\Processor\NodeName;
use Prooph\Processing\Type\Prototype;

/**
 * Class MessageHandler
 *
 * The aggregate represents a processing workflow message handler in the process manager model.
 * It is not the real message handler implementation but contains information and provides methods to deal with a
 * message handler within the process manager domain.
 *
 * @package Prooph\Link\ProcessManager\Model
 * @author Alexander Miertsch <kontakt@codeliner.ws>
 */
final class MessageHandler extends AggregateRoot
{
    /**
     * Unique identifier of the message handler across the process manager domain
     *
     * @var MessageHandlerId
     */
    private $messageHandlerId;

    /**
     * Name of the message handler, defined by the client
     *
     * @var string
     */
    private $name;

    /**
     * Connected process task
     *
     * @var TaskId
     */
    private $taskId;

    /**
     * Defines either the handler acts as a source (collects data) or as a target (processes data)
     *
     * @var DataDirection
     */
    private $dataDirection;

    /**
     * Defines the type of the message handler
     *
     * @var HandlerType
     */
    private $handlerType;

    /**
     * Defines the processing node on which the handler is installed
     *
     * @var NodeName
     */
    private $processingNodeName;

    /**
     * Defines the identifier of the message handler implementation in the processing system
     *
     * @var ProcessingId
     */
    private $processingId;

    /**
     * @var ProcessingTypes
     */
    private $supportedProcessingTypes;

    /**
     * @var Prototype
     */
    private $preferredProcessingType;

    /**
     * Contains the processing metadata for the processing message handler implementation
     *
     * @var ProcessingMetadata
     */
    private $processingMetadata;

    /**
     * @param MessageHandlerId $id
     * @param string $name
     * @param NodeName $nodeName
     * @param MessageHandler\HandlerType $handlerType
     * @param MessageHandler\DataDirection $dataDirection
     * @param MessageHandler\ProcessingTypes $supportedProcessingTypes
     * @param ProcessingMetadata $metadata
     * @param null|Prototype $preferredProcessingType
     * @param null|MessageHandler\ProcessingId $processingId
     * @return MessageHandler
     */
    public static function fromDefinition(
        MessageHandlerId $id,
        $name,
        NodeName $nodeName,
        HandlerType $handlerType,
        DataDirection $dataDirection,
        ProcessingTypes $supportedProcessingTypes,
        ProcessingMetadata $metadata,
        Prototype $preferredProcessingType = null,
        ProcessingId $processingId = null
    ) {
        Assertion::string($name);
        Assertion::notEmpty($name);

        $instance = new self();

        $instance->recordThat(MessageHandlerWasCreated::occur(
            $id->toString(),
            [
                'name' => $name,
                'processing_node_name' => $nodeName->toString(),
                'handler_type' => $handlerType->toString(),
                'data_direction' => $dataDirection->toString(),
                'supported_processing_types' => $supportedProcessingTypes->toArray(),
                'processing_metadata' => $metadata->toArray(),
                'preferred_processing_type' => ($preferredProcessingType)? $preferredProcessingType->of() : null,
                'processing_id' => ($processingId)? $processingId->toString() : null,
            ]
        ));

        return $instance;
    }

    /**
     * @return MessageHandlerId
     */
    public function messageHandlerId()
    {
        return $this->messageHandlerId;
    }

    /**
     * @return string
     */
    public function name()
    {
        return $this->name;
    }

    /**
     * @param Message $message
     * @return bool
     */
    public function canHandleMessage(Message $message)
    {
        if (! $this->canHandleMessageType($message->messageType())) {
            return false;
        }

        return $this->supportedProcessingTypes->isSupported($message->processingType());
    }

    /**
     * @param MessageType $messageType
     * @return bool
     */
    private function canHandleMessageType(MessageType $messageType)
    {
        if ($this->handlerType->isConnector()) {
            if ($messageType->isCollectDataMessage() && $this->dataDirection->isSource()) {
                return true;
            }

            if ($messageType->isProcessDataMessage() && $this->dataDirection->isTarget()) {
                return true;
            }
        } elseif ($this->handlerType->isScript()) {
            if (! $messageType->isProcessDataMessage()) {
                return true;
            }
        } else {
            //HandlerType is callback, so handler can handle all message types
            return true;
        }

        //No matching until now, so handler is not able to handle the message type
        return false;
    }

    /**
     * @param MessageHandlerWasCreated $event
     */
    protected function whenMessageHandlerWasCreated(MessageHandlerWasCreated $event)
    {
        $this->messageHandlerId = $event->messageHandlerId();
        $this->name = $event->messageHandlerName();
        $this->processingNodeName = $event->processingNodeName();
        $this->handlerType = $event->handlerType();
        $this->dataDirection = $event->dataDirection();
        $this->supportedProcessingTypes = $event->supportedProcessingTypes();
        $this->preferredProcessingType = $event->preferredProcessingType();
        $this->processingMetadata = $event->processingMetadata();
        $this->processingId = $event->processingId();
    }

    /**
     * @return string representation of the unique identifier of the aggregate root
     */
    protected function aggregateId()
    {
        return $this->messageHandlerId->toString();
    }
}