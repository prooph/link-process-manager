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
use Prooph\Common\Messaging\Command;
use Prooph\Link\ProcessManager\Model\MessageHandler\DataDirection;
use Prooph\Link\ProcessManager\Model\MessageHandler\HandlerType;
use Prooph\Link\ProcessManager\Model\MessageHandler\MessageHandlerId;
use Prooph\Link\ProcessManager\Model\MessageHandler\ProcessingId;
use Prooph\Link\ProcessManager\Model\MessageHandler\ProcessingTypes;
use Prooph\Link\ProcessManager\Model\ProcessingMetadata;
use Prooph\Processing\Processor\NodeName;
use Prooph\Processing\Type\Prototype;
use Prooph\Processing\Type\Type;

/**
 * Class InstallMessageHandler
 *
 * @package Prooph\Link\ProcessManager\Command\MessageHandler
 * @author Alexander Miertsch <kontakt@codeliner.ws>
 */
final class InstallMessageHandler extends Command
{
    /**
     * @param string $messageHandlerId
     * @param string $name
     * @param string $nodeName
     * @param string $handlerType
     * @param string $dataDirection
     * @param array|string $supportedProcessingTypes
     * @param array $processingMetadata
     * @param string $metadataRiotTag
     * @param string $icon
     * @param string $iconType
     * @param null|string $preferredProcessingType
     * @param null|string $handlerProcessingId
     * @param array $additionalData
     * @return InstallMessageHandler
     */
    public static function withData(
        $messageHandlerId,
        $name,
        $nodeName,
        $handlerType,
        $dataDirection,
        $supportedProcessingTypes,
        array $processingMetadata,
        $metadataRiotTag,
        $icon,
        $iconType,
        $preferredProcessingType = null,
        $handlerProcessingId = null,
        array $additionalData = []
    ) {
        Assertion::uuid($messageHandlerId);
        Assertion::string($name);
        Assertion::notEmpty($name);
        Assertion::string($nodeName);
        Assertion::notEmpty($nodeName);
        Assertion::string($handlerType);
        Assertion::string($dataDirection);
        Assertion::string($metadataRiotTag);
        Assertion::string($icon);
        Assertion::string($iconType);

        if (! is_null($preferredProcessingType)) {
            Assertion::string($preferredProcessingType);
            Assertion::implementsInterface($preferredProcessingType, Type::class);
        }

        if (! is_null($handlerProcessingId)) {
            Assertion::string($handlerProcessingId);
        }

        if (!is_string($supportedProcessingTypes)) {
            Assertion::isArray($supportedProcessingTypes);
        }

        return new self(
            __CLASS__,
            [
                'message_handler_id' => $messageHandlerId,
                'name' => $name,
                'node_name' => $nodeName,
                'handler_type' => $handlerType,
                'data_direction' => $dataDirection,
                'supported_processing_types' => $supportedProcessingTypes,
                'processing_metadata' => $processingMetadata,
                'metadata_riot_tag' => $metadataRiotTag,
                'icon' => $icon,
                'icon_type' => $iconType,
                'preferred_processing_type' => $preferredProcessingType,
                'handler_processing_id' => $handlerProcessingId,
                'additional_data' => $additionalData
            ]
        );
    }

    /**
     * @return MessageHandlerId
     */
    public function messageHandlerId()
    {
        return MessageHandlerId::fromString($this->payload['message_handler_id']);
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
    public function nodeName()
    {
        return NodeName::fromString($this->payload['node_name']);
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
        $supportedProcessingTypes = $this->payload['supported_processing_types'];

        if (is_string($supportedProcessingTypes) && $supportedProcessingTypes === ProcessingTypes::SUPPORT_ALL) {
            $supportedProcessingTypes = ProcessingTypes::supportAll();
        } else {
            $supportedProcessingTypes = ProcessingTypes::support($supportedProcessingTypes);
        }
        return $supportedProcessingTypes;
    }

    /**
     * @return ProcessingMetadata
     */
    public function processingMetadata()
    {
        return ProcessingMetadata::fromArray($this->payload['processing_metadata']);
    }

    /**
     * @return string
     */
    public function metadataRiotTag()
    {
        return $this->payload['metadata_riot_tag'];
    }

    /**
     * @return string
     */
    public function icon()
    {
        return $this->payload['icon'];
    }

    /**
     * @return string
     */
    public function iconType()
    {
        return $this->payload['icon_type'];
    }

    /**
     * @return Prototype|null
     */
    public function preferredProcessingType()
    {
        $type = $this->payload['preferred_processing_type'];

        if ($type) return $type::prototype();
        return null;
    }

    /**
     * @return ProcessingId|null
     */
    public function handlerProcessingId()
    {
        if (! is_null($this->payload['handler_processing_id'])) {
            return ProcessingId::fromString($this->payload['handler_processing_id']);
        }

        return null;
    }

    /**
     * @return array
     */
    public function additionalData()
    {
        return $this->payload['additional_data'];
    }
}