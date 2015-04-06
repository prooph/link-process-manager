<?php
/*
 * This file is part of prooph/link.
 * (c) 2014-2015 prooph software GmbH <contact@prooph.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 * 
 * Date: 4/5/15 - 8:17 PM
 */
namespace Prooph\Link\ProcessManager\Model\MessageHandler;
use Assert\Assertion;

/**
 * Class ProcessingId
 *
 * Defines the identifier of the message handler in the processing config.
 * Different MessageHandler can have the same ProcessingId, because they can be used in different Workflows or Processes
 * but share the same basic configuration.
 * The ProcessingId is defined by the module which owns the MessageHandler implementation.
 * The ProcessingId is also used to define the source or target of a task. In the processing system this identifier
 * is passed to the service locator to retrieve the message handler instance.
 *
 * @package Prooph\Link\ProcessManager\Model\MessageHandler
 * @author Alexander Miertsch <kontakt@codeliner.ws>
 */
final class ProcessingId 
{
    /**
     * @var string
     */
    private $processingId;

    /**
     * @param string $processingId
     * @return ProcessingId
     */
    public static function fromString($processingId)
    {
        return new self($processingId);
    }

    /**
     * @param string $processingId
     */
    private function __construct($processingId)
    {
        Assertion::string($processingId);
        Assertion::notEmpty($processingId);

        $this->processingId = $processingId;
    }

    /**
     * @return string
     */
    public function toString()
    {
        return $this->processingId;
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return $this->toString();
    }

    /**
     * @param ProcessingId $other
     * @return bool
     */
    public function equals(ProcessingId $other)
    {
        return $this->toString() === $other->toString();
    }
} 