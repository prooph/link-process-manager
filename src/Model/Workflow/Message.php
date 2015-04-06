<?php
/*
 * This file is part of prooph/link.
 * (c) 2014-2015 prooph software GmbH <contact@prooph.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 * 
 * Date: 4/5/15 - 9:36 PM
 */
namespace Prooph\Link\ProcessManager\Model\Workflow;

use Prooph\Link\ProcessManager\Model\ProcessingMetadata;
use Prooph\Processing\Type\Prototype;

/**
 * Class Message
 *
 * Represents a workflow message that would be sent in the processing system to communicate with a handler or processor.
 *
 * @package Prooph\Link\ProcessManager\Model\Workflow
 * @author Alexander Miertsch <kontakt@codeliner.ws>
 */
final class Message 
{
    /**
     * @var MessageType
     */
    private $messageType;

    /**
     * @var Prototype
     */
    private $processingType;

    /**
     * Processing metadata that will be sent along with the message
     *
     * @var ProcessingMetadata
     */
    private $processingMetadata;

    /**
     * @param MessageType $messageType
     * @param Prototype $prototype
     * @param ProcessingMetadata $metadata
     * @return Message
     */
    public static function emulateProcessingWorkflowMessage(MessageType $messageType, Prototype $prototype, ProcessingMetadata $metadata)
    {
        return new self($messageType, $prototype, $metadata);
    }

    /**
     * @param MessageType $messageType
     * @param Prototype $prototype
     * @param ProcessingMetadata $metadata
     */
    private function __construct(MessageType $messageType, Prototype $prototype, ProcessingMetadata $metadata)
    {
        $this->messageType = $messageType;
        $this->processingType = $prototype;
        $this->processingMetadata = $metadata;
    }

    /**
     * @return MessageType
     */
    public function messageType()
    {
        return $this->messageType;
    }

    /**
     * @return Prototype
     */
    public function processingType()
    {
        return $this->processingType;
    }

    /**
     * @return ProcessingMetadata
     */
    public function processingMetadata()
    {
        return $this->processingMetadata;
    }

    /**
     * @param Message $other
     * @return bool
     */
    public function equals(Message $other)
    {
        return $this->messageType()->equals($other->messageType())
            && $this->processingType()->of() === $other->processingType()->of()
            && $this->processingMetadata()->toArray() === $other->processingMetadata()->toArray();
    }
} 