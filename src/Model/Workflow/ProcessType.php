<?php
/*
 * This file is part of prooph/link.
 * (c) 2014-2015 prooph software GmbH <contact@prooph.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 * 
 * Date: 4/6/15 - 4:03 PM
 */
namespace Prooph\Link\ProcessManager\Model\Workflow;

use Assert\Assertion;
use Prooph\Processing\Processor\Definition;

/**
 * Class ProcessType
 *
 * This value object defines the processing process type of a process in the process manager domain.
 * The type defines how tasks are handled by the process. The type is required to add the process to the processing configuration.
 * In the processing domain exist a process class for each type. The processing process factory is responsible for setting up
 * a process based on its type. Therefor we need to use the exact same types in the process manager domain to support the factory.
 *
 * @package Prooph\Link\ProcessManager\Model\Workflow
 * @author Alexander Miertsch <kontakt@codeliner.ws>
 */
final class ProcessType 
{
    const TYPE_LINEAR_MESSAGING = Definition::PROCESS_LINEAR_MESSAGING;
    const TYPE_PARALLEL_FOREACH = Definition::PROCESS_PARALLEL_FOR_EACH;
    const TYPE_PARALLEL_CHUNK   = Definition::PROCESS_PARALLEL_CHUNK;
    const TYPE_PARALLEL_FORK    = Definition::PROCESS_PARALLEL_FORK;

    private $allowedTypes = [self::TYPE_LINEAR_MESSAGING, self::TYPE_PARALLEL_FOREACH, self::TYPE_PARALLEL_CHUNK, self::TYPE_PARALLEL_FORK];

    /**
     * @var string
     */
    private $processType;

    /**
     * This type is useful when tasks need to be processed one by one.
     *
     * @return ProcessType
     */
    public static function linearMessaging()
    {
        return new self(self::TYPE_LINEAR_MESSAGING);
    }

    /**
     * This type is useful when the source processing type is a collection  but the target can only handle
     * one item per message.
     *
     * @return ProcessType
     */
    public static function parallelForeach()
    {
        return new self(self::TYPE_PARALLEL_FOREACH);
    }

    /**
     * This type is useful when a source collection should be processed in chunks.
     *
     * @return ProcessType
     */
    public static function parallelChunk()
    {
        return new self(self::TYPE_PARALLEL_CHUNK);
    }

    /**
     * This type is useful when the source processing type should be handled by different targets independently.
     *
     * @return ProcessType
     */
    public static function parallelFork()
    {
        return new self(self::TYPE_PARALLEL_FORK);
    }

    /**
     * @param string $processType
     * @return ProcessType
     */
    public static function fromString($processType)
    {
        return new self($processType);
    }

    /**
     * @param string $processType
     */
    private function __construct($processType)
    {
        Assertion::inArray($processType, $this->allowedTypes);
        $this->processType = $processType;
    }

    /**
     * @return bool
     */
    public function isLinearMessaging()
    {
        return $this->processType === self::TYPE_LINEAR_MESSAGING;
    }

    /**
     * @return bool
     */
    public function isParallelForeach()
    {
        return $this->processType === self::TYPE_PARALLEL_FOREACH;
    }

    /**
     * @return bool
     */
    public function isParallelChunk()
    {
        return $this->processType === self::TYPE_PARALLEL_CHUNK;
    }

    /**
     * @return bool
     */
    public function isParallelFork()
    {
        return $this->processType === self::TYPE_PARALLEL_FORK;
    }

    /**
     * @return string
     */
    public function toString()
    {
        return $this->processType;
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return $this->toString();
    }

    /**
     * @param ProcessType $other
     * @return bool
     */
    public function equals(ProcessType $other)
    {
        return $this->toString() === $other->toString();
    }
} 