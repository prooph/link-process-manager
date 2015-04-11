<?php
/*
 * This file is part of prooph/link.
 * (c) 2014-2015 prooph software GmbH <contact@prooph.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 * 
 * Date: 4/5/15 - 9:43 PM
 */
namespace Prooph\Link\ProcessManager\Model\Workflow;
use Assert\Assertion;

/**
 * Class MessageType
 *
 * This value object can be one of the known processing workflow message types.
 *
 * @package Prooph\Link\ProcessManager\Model\Workflow
 * @author Alexander Miertsch <kontakt@codeliner.ws>
 */
final class MessageType 
{
    const TYPE_COLLECT_DATA = "collect-data";
    const TYPE_DATA_COLLECTED = "data-collected";
    const TYPE_PROCESS_DATA = "process-data";
    const TYPE_DATA_PROCESSED = "data-processed";

    /**
     * @var array
     */
    private $allowedTypes = [self::TYPE_COLLECT_DATA, self::TYPE_DATA_COLLECTED, self::TYPE_PROCESS_DATA, self::TYPE_DATA_PROCESSED];

    /**
     * @var string
     */
    private $messageType;

    /**
     * @return MessageType
     */
    public static function collectData()
    {
        return new self(self::TYPE_COLLECT_DATA);
    }

    /**
     * @return MessageType
     */
    public static function dataCollected()
    {
        return new self(self::TYPE_DATA_COLLECTED);
    }

    /**
     * @return MessageType
     */
    public static function processData()
    {
        return new self(self::TYPE_PROCESS_DATA);
    }

    /**
     * @return MessageType
     */
    public static function dataProcessed()
    {
        return new self(self::TYPE_DATA_PROCESSED);
    }

    /**
     * @param string $messageType
     * @return MessageType
     */
    public static function fromString($messageType)
    {
        return new self($messageType);
    }

    /**
     * @param string $messageType
     */
    private function __construct($messageType)
    {
        Assertion::inArray($messageType, $this->allowedTypes);

        $this->messageType = $messageType;
    }

    /**
     * @return bool
     */
    public function isCollectDataMessage()
    {
        return $this->messageType === self::TYPE_COLLECT_DATA;
    }

    /**
     * @return bool
     */
    public function isDataCollectedMessage()
    {
        return $this->messageType === self::TYPE_DATA_COLLECTED;
    }

    /**
     * @return bool
     */
    public function isProcessDataMessage()
    {
        return $this->messageType === self::TYPE_PROCESS_DATA;
    }

    /**
     * @return bool
     */
    public function isDataProcessedMessage()
    {
        return $this->messageType === self::TYPE_DATA_PROCESSED;
    }

    /**
     * @return bool
     */
    public function isEvent()
    {
        return $this->isDataCollectedMessage() || $this->isDataProcessedMessage();
    }

    /**
     * @return bool
     */
    public function isCommand()
    {
        return $this->isCollectDataMessage() || $this->isProcessDataMessage();
    }

    /**
     * @return string
     */
    public function toString()
    {
        return $this->messageType;
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return $this->toString();
    }

    /**
     * @param MessageType $other
     * @return bool
     */
    public function equals(MessageType $other)
    {
        return $this->toString() === $other->toString();
    }
} 