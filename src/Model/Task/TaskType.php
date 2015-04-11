<?php
/*
 * This file is part of prooph/link.
 * (c) 2014-2015 prooph software GmbH <contact@prooph.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 * 
 * Date: 4/6/15 - 5:25 PM
 */
namespace Prooph\Link\ProcessManager\Model\Task;

use Assert\Assertion;
use Prooph\Processing\Processor\Definition;

/**
 * Class TaskType
 *
 * The value object defines possible types for a task. The types are the same as defined in the processing domain and
 * are required to successfully add a task to the processing configuration.
 *
 * @package Prooph\Link\ProcessManager\Model\Task
 * @author Alexander Miertsch <kontakt@codeliner.ws>
 */
final class TaskType 
{
    const TYPE_COLLECT_DATA       = Definition::TASK_COLLECT_DATA;
    const TYPE_PROCESS_DATA       = Definition::TASK_PROCESS_DATA;
    const TYPE_RUN_SUB_PROCESS    = Definition::TASK_RUN_SUB_PROCESS;
    const TYPE_MANIPULATE_PAYLOAD = Definition::TASK_MANIPULATE_PAYLOAD;

    private $allowedTypes = [self::TYPE_COLLECT_DATA, self::TYPE_PROCESS_DATA, self::TYPE_RUN_SUB_PROCESS, self::TYPE_MANIPULATE_PAYLOAD];

    /**
     * @var string
     */
    private $type;

    /**
     * @return TaskType
     */
    public static function collectData()
    {
        return new self(self::TYPE_COLLECT_DATA);
    }

    /**
     * @return TaskType
     */
    public static function processData()
    {
        return new self(self::TYPE_PROCESS_DATA);
    }

    /**
     * @return TaskType
     */
    public static function runSubProcess()
    {
        return new self(self::TYPE_RUN_SUB_PROCESS);
    }

    /**
     * @return TaskType
     */
    public static function manipulatePayload()
    {
        return new self(self::TYPE_MANIPULATE_PAYLOAD);
    }

    /**
     * @param string $taskType
     * @return TaskType
     */
    public static function fromString($taskType)
    {
        return new self($taskType);
    }

    /**
     * @param string $type
     */
    private function __construct($type)
    {
        Assertion::inArray($type, $this->allowedTypes);
        $this->type = $type;
    }

    /**
     * @return bool
     */
    public function isCollectData()
    {
        return $this->type === self::TYPE_COLLECT_DATA;
    }

    /**
     * @return bool
     */
    public function isProcessData()
    {
        return $this->type === self::TYPE_PROCESS_DATA;
    }

    /**
     * @return bool
     */
    public function isRunSubProcess()
    {
        return $this->type === self::TYPE_RUN_SUB_PROCESS;
    }

    /**
     * @return bool
     */
    public function isManipulatePayload()
    {
        return $this->type === self::TYPE_MANIPULATE_PAYLOAD;
    }

    /**
     * @return string
     */
    public function toString()
    {
        return $this->type;
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return $this->toString();
    }

    /**
     * @param TaskType $other
     * @return bool
     */
    public function equals(TaskType $other)
    {
        return $this->toString() === $other->toString();
    }
} 