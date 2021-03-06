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
use Prooph\Link\ProcessManager\Model\MessageHandler\Exception\DataDirectionIsNotSupported;
use Prooph\Link\ProcessManager\Model\MessageHandler\Exception\ProcessingTypeIsNotSupported;
use Prooph\Link\ProcessManager\Model\MessageHandler\HandlerType;
use Prooph\Link\ProcessManager\Model\MessageHandler\MessageHandlerId;
use Prooph\Link\ProcessManager\Model\MessageHandler\MessageHandlerWasInstalled;
use Prooph\Link\ProcessManager\Model\MessageHandler\ProcessingId;
use Prooph\Link\ProcessManager\Model\MessageHandler\ProcessingTypes;
use Prooph\Link\ProcessManager\Model\Task\TaskId;
use Prooph\Link\ProcessManager\Model\Workflow\Exception\MessageIsNotManageable;
use Prooph\Link\ProcessManager\Model\Workflow\Message;
use Prooph\Link\ProcessManager\Model\Workflow\MessageType;
use Prooph\Processing\Processor\NodeName;
use Prooph\Processing\Type\Prototype;

/**
 * Aggregate MessageHandler
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
     * @var string
     */
    private $metadataRiotTag;

    /**
     * @var string
     */
    private $icon;

    /**
     * @var string
     */
    private $iconType;

    /**
     * @var array
     */
    private $additionalData;

    /**
     * Stores the reason in case the message handler can't handle a message.
     *
     * This property is reset each time the {@method canHandleMessage} is called.
     * If the check fails the {@method lastValidationError} can be used to get the reason of the failing validation.
     *
     * @var string
     */
    private $lastValidationError;

    /**
     * @param MessageHandlerId $id
     * @param string $name
     * @param NodeName $nodeName
     * @param MessageHandler\HandlerType $handlerType
     * @param MessageHandler\DataDirection $dataDirection
     * @param MessageHandler\ProcessingTypes $supportedProcessingTypes
     * @param ProcessingMetadata $metadata
     * @param string $metadataRiotTag
     * @param string $icon
     * @param string $iconType
     * @param null|Prototype $preferredProcessingType
     * @param null|MessageHandler\ProcessingId $processingId
     * @param array $additionalData
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
        $metadataRiotTag,
        $icon,
        $iconType,
        Prototype $preferredProcessingType = null,
        ProcessingId $processingId = null,
        array $additionalData = []
    ) {
        Assertion::string($name);
        Assertion::notEmpty($name);

        Assertion::string($metadataRiotTag);
        Assertion::notEmpty($metadataRiotTag);

        Assertion::string($icon);
        Assertion::notEmpty($icon);

        Assertion::string($iconType);
        Assertion::notEmpty($iconType);

        $instance = new self();

        $instance->recordThat(MessageHandlerWasInstalled::record(
            $id,
            $name,
            $nodeName,
            $handlerType,
            $dataDirection,
            $supportedProcessingTypes,
            $metadata,
            $metadataRiotTag,
            $icon,
            $iconType,
            $preferredProcessingType,
            $processingId,
            $additionalData
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
     * @return HandlerType
     */
    public function handlerType()
    {
        return $this->handlerType;
    }

    /**
     * @return NodeName
     */
    public function processingNodeName()
    {
        return $this->processingNodeName;
    }

    /**
     * @return ProcessingMetadata
     */
    public function processingMetadata()
    {
        return $this->processingMetadata;
    }

    /**
     * @return DataDirection
     */
    public function dataDirection()
    {
        return $this->dataDirection;
    }

    /**
     * @return ProcessingTypes
     */
    public function supportedProcessingTypes()
    {
        return $this->supportedProcessingTypes;
    }

    /**
     * @return null|Prototype
     */
    public function preferredProcessingType()
    {
        return $this->preferredProcessingType;
    }

    /**
     * @return null|ProcessingId
     */
    public function processingId()
    {
        return $this->processingId;
    }

    /**
     * @return bool
     */
    public function isKnownInProcessingSystem()
    {
        return ! is_null($this->processingId);
    }

    /**
     * @return string
     */
    public function icon()
    {
        return $this->icon;
    }

    /**
     * @return string
     */
    public function iconType()
    {
        return $this->iconType;
    }

    /**
     * @return string
     */
    public function metadataRiotTag()
    {
        return $this->metadataRiotTag;
    }

    /**
     * @return array
     */
    public function additionalData()
    {
        return $this->additionalData;
    }

    /**
     * @param Message $message
     * @return bool
     */
    public function canHandleMessage(Message $message)
    {
        $this->lastValidationError = null;

        if (! $this->canHandleMessageType($message->messageType())) {
            $this->lastValidationError = sprintf(
                "Message type %s is not supported.",
                $message->messageType()
            );
            return false;
        }

        if ($message->processingMetadata()->shouldCollectionBeSplitIntoChunks()) {
            if (! $this->processingMetadata->canHandleChunks()) {
                $this->lastValidationError = "Unable to handle chunks.";
                return false;
            }
        }

        if (! $this->supportedProcessingTypes->isSupported($message->processingType())) {
            $this->lastValidationError = sprintf(
                "Processing type %s is not supported. Supported types are: %s",
                $message->processingType()->of(),
                implode(", ", $this->supportedProcessingTypes->toArray()['processing_types'])
            );
            return false;
        }

        return true;
    }

    /**
     * @param Task $connectedTask
     * @return Message
     * @throws Workflow\Exception\MessageIsNotManageable
     */
    public function emulateAnswerMessage(Task $connectedTask)
    {
        if (! $this->canHandleMessage($connectedTask->emulateWorkflowMessage())) {
            throw MessageIsNotManageable::byMessageHandler($this, $connectedTask->emulateWorkflowMessage());
        }

        if ($connectedTask->type()->isCollectData()) {
            $messageType = MessageType::dataCollected();
        } else {
            $messageType = MessageType::dataProcessed();
        }

        return Message::emulateProcessingWorkflowMessage($messageType, $connectedTask->processingType(), $connectedTask->metadata());
    }

    /**
     * @return string|null
     */
    public function lastValidationError()
    {
        return $this->lastValidationError;
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
            if ($messageType->isProcessDataMessage()) {
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
     * @param MessageHandlerWasInstalled $event
     */
    protected function whenMessageHandlerWasInstalled(MessageHandlerWasInstalled $event)
    {
        $this->messageHandlerId = $event->messageHandlerId();
        $this->name = $event->messageHandlerName();
        $this->processingNodeName = $event->processingNodeName();
        $this->handlerType = $event->handlerType();
        $this->dataDirection = $event->dataDirection();
        $this->supportedProcessingTypes = $event->supportedProcessingTypes();
        $this->preferredProcessingType = $event->preferredProcessingType();
        $this->processingMetadata = $event->processingMetadata();
        $this->metadataRiotTag = $event->metadataRiotTag();
        $this->icon = $event->icon();
        $this->iconType = $event->iconType();
        $this->processingId = $event->processingId();
        $this->additionalData = $event->additionalData();
    }

    /**
     * @return string representation of the unique identifier of the aggregate root
     */
    protected function aggregateId()
    {
        return $this->messageHandlerId->toString();
    }
}