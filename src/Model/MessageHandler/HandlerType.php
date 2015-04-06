<?php
/*
 * This file is part of prooph/link.
 * (c) 2014-2015 prooph software GmbH <contact@prooph.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 * 
 * Date: 4/5/15 - 7:58 PM
 */
namespace Prooph\Link\ProcessManager\Model\MessageHandler;

use Assert\Assertion;

/**
 * Class HandlerType
 *
 * This value object defines the type of the message handler. This can be a connector, script or callback.
 *
 * @package Prooph\Link\ProcessManager\Model\MessageHandler
 * @author Alexander Miertsch <kontakt@codeliner.ws>
 */
final class HandlerType 
{
    const TYPE_CONNECTOR = 'connector';
    const TYPE_SCRIPT = 'script';
    const TYPE_CALLBACK = 'callback';

    private $allowedTypes = [self::TYPE_CONNECTOR, self::TYPE_SCRIPT, self::TYPE_CALLBACK];

    /**
     * @var string
     */
    private $handlerType;

    /**
     * @return HandlerType
     */
    public static function connector()
    {
        return new self(self::TYPE_CONNECTOR);
    }

    /**
     * @return HandlerType
     */
    public static function script()
    {
        return new self(self::TYPE_SCRIPT);
    }

    /**
     * @return HandlerType
     */
    public static function callback()
    {
        return new self(self::TYPE_CALLBACK);
    }

    /**
     * @param string $handlerType
     * @return HandlerType
     */
    public static function fromString($handlerType)
    {
        return new self($handlerType);
    }

    /**
     * @param string $handlerType
     */
    private function __construct($handlerType)
    {
        Assertion::inArray($handlerType, $this->allowedTypes);
        $this->handlerType = $handlerType;
    }

    /**
     * @return bool
     */
    public function isConnector()
    {
        return self::TYPE_CONNECTOR === $this->handlerType;
    }

    /**
     * @return bool
     */
    public function isScript()
    {
        return self::TYPE_SCRIPT === $this->handlerType;
    }

    /**
     * @return bool
     */
    public function isCallback()
    {
        return self::TYPE_CALLBACK === $this->handlerType;
    }

    /**
     * @return string
     */
    public function toString()
    {
        return $this->handlerType;
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return $this->toString();
    }

    /**
     * @param HandlerType $other
     * @return bool
     */
    public function equals(HandlerType $other)
    {
        return $this->toString() === $other->toString();
    }
} 