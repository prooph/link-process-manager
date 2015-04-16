<?php
/*
 * This file is part of prooph/link.
 * (c) 2014-2015 prooph software GmbH <contact@prooph.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 * 
 * Date: 4/5/15 - 9:17 PM
 */
namespace Prooph\Link\ProcessManager\Command\MessageHandler;

use Assert\Assertion;
use Prooph\Link\Application\Service\TransactionCommand;
use Prooph\Link\Application\Service\TransactionIdGenerator;
use Prooph\Link\ProcessManager\Model\MessageHandler\DataDirection;
use Prooph\Link\ProcessManager\Model\MessageHandler\HandlerType;
use Prooph\Link\ProcessManager\Model\MessageHandler\MessageHandlerId;
use Prooph\Link\ProcessManager\Model\MessageHandler\ProcessingId;
use Prooph\Link\ProcessManager\Model\MessageHandler\ProcessingTypes;
use Prooph\Link\ProcessManager\Model\ProcessingMetadata;
use Prooph\Processing\Processor\NodeName;
use Prooph\Processing\Type\Prototype;
use Prooph\Processing\Type\Type;
use Prooph\ServiceBus\Message\MessageNameProvider;

/**
 * Class InstallMessageHandler
 *
 * @package Prooph\Link\ProcessManager\Command\MessageHandler
 * @author Alexander Miertsch <kontakt@codeliner.ws>
 */
final class InstallMessageHandler implements TransactionCommand, MessageNameProvider
{
    use TransactionIdGenerator;

    /**
     * @var MessageHandlerId
     */
    private $messageHandlerId;

    /**
     * @var string
     */
    private $name;

    /**
     * @var NodeName
     */
    private $nodeName;

    /**
     * @var HandlerType
     */
    private $handlerType;

    /**
     * @var DataDirection
     */
    private $dataDirection;

    /**
     * @var ProcessingTypes
     */
    private $processingTypes;

    /**
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
     * @var Prototype
     */
    private $preferredProcessingType;

    /**
     * @var ProcessingId
     */
    private $handlerProcessingId;

    /**
     * @param MessageHandlerId|string $messageHandlerId
     * @param string $name
     * @param NodeName|string $nodeName
     * @param HandlerType|string $handlerType
     * @param DataDirection|string $dataDirection
     * @param ProcessingTypes|array|string $supportedProcessingTypes
     * @param ProcessingMetadata|array $processingMetadata
     * @param string $metadataRiotTag
     * @param string $icon
     * @param string $iconType
     * @param null|string|Prototype $preferredProcessingType
     * @param null|string|ProcessingId $handlerProcessingId
     */
    public function __construct(
        $messageHandlerId,
        $name,
        $nodeName,
        $handlerType,
        $dataDirection,
        $supportedProcessingTypes,
        $processingMetadata,
        $metadataRiotTag,
        $icon,
        $iconType,
        $preferredProcessingType = null,
        $handlerProcessingId = null
    ) {
        Assertion::string($name);
        Assertion::notEmpty($name);

        if (! $messageHandlerId instanceof MessageHandlerId) {
            $messageHandlerId = MessageHandlerId::fromString($messageHandlerId);
        }

        if (! $nodeName instanceof NodeName) {
            $nodeName = NodeName::fromString($nodeName);
        }

        if (! $handlerType instanceof HandlerType) {
            $handlerType = HandlerType::fromString($handlerType);
        }

        if (! $dataDirection instanceof DataDirection) {
            $dataDirection = DataDirection::fromString($dataDirection);
        }

        if (! $supportedProcessingTypes instanceof ProcessingTypes) {
            if (is_string($supportedProcessingTypes) && $supportedProcessingTypes === ProcessingTypes::SUPPORT_ALL) {
               $supportedProcessingTypes = ProcessingTypes::supportAll();
            } else {
                $supportedProcessingTypes = ProcessingTypes::support($supportedProcessingTypes);
            }
        }

        if (! $processingMetadata instanceof ProcessingMetadata) {
            $processingMetadata = ProcessingMetadata::fromArray($processingMetadata);
        }

        if (! is_null($preferredProcessingType) && ! $preferredProcessingType instanceof Prototype) {
            Assertion::string($preferredProcessingType);
            Assertion::classExists($preferredProcessingType);
            Assertion::implementsInterface($preferredProcessingType, Type::class);
            $preferredProcessingType = $preferredProcessingType::prototype();
        }

        if (! is_null($handlerProcessingId) && ! $handlerProcessingId instanceof ProcessingId) {
            $handlerProcessingId = ProcessingId::fromString($handlerProcessingId);
        }

        Assertion::string($metadataRiotTag);
        Assertion::notEmpty($metadataRiotTag);

        Assertion::string($icon);
        Assertion::notEmpty($icon);

        Assertion::string($iconType);
        Assertion::notEmpty($iconType);

        $this->messageHandlerId = $messageHandlerId;
        $this->name = $name;
        $this->nodeName = $nodeName;
        $this->handlerType = $handlerType;
        $this->dataDirection = $dataDirection;
        $this->processingTypes = $supportedProcessingTypes;
        $this->processingMetadata = $processingMetadata;
        $this->metadataRiotTag = $metadataRiotTag;
        $this->icon = $icon;
        $this->iconType = $iconType;
        $this->preferredProcessingType = $preferredProcessingType;
        $this->handlerProcessingId = $handlerProcessingId;
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
    public function messageHandlerName()
    {
        return $this->name;
    }

    /**
     * @return NodeName
     */
    public function nodeName()
    {
        return $this->nodeName;
    }

    /**
     * @return HandlerType
     */
    public function handlerType()
    {
        return $this->handlerType;
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
        return $this->processingTypes;
    }

    /**
     * @return ProcessingMetadata
     */
    public function processingMetadata()
    {
        return $this->processingMetadata;
    }

    /**
     * @return string
     */
    public function metadataRiotTag()
    {
        return $this->metadataRiotTag;
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
     * @return Prototype|null
     */
    public function preferredProcessingType()
    {
        return $this->preferredProcessingType;
    }

    /**
     * @return ProcessingId|null
     */
    public function handlerProcessingId()
    {
        return $this->handlerProcessingId;
    }

    /**
     * @return string Name of the message
     */
    public function getMessageName()
    {
        return __CLASS__;
    }
}