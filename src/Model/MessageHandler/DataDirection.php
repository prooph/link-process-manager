<?php
/*
 * This file is part of prooph/link.
 * (c) 2014-2015 prooph software GmbH <contact@prooph.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 * 
 * Date: 4/5/15 - 1:40 AM
 */
namespace Prooph\Link\ProcessManager\Model\MessageHandler;

use Assert\Assertion;

/**
 * Class DataDirection
 *
 * @package Prooph\Link\ProcessManager\Model\MessageHandler
 * @author Alexander Miertsch <kontakt@codeliner.ws>
 */
final class DataDirection 
{
    const DIRECTION_SOURCE = "source";
    const DIRECTION_TARGET = "target";

    /**
     * @var string
     */
    private $direction;

    /**
     * @var array
     */
    private $allowedDirections = [self::DIRECTION_SOURCE, self::DIRECTION_TARGET];

    /**
     * @return DataDirection
     */
    public static function source()
    {
        return new self(self::DIRECTION_SOURCE);
    }

    /**
     * @return DataDirection
     */
    public static function target()
    {
        return new self(self::DIRECTION_TARGET);
    }

    /**
     * @param $direction
     */
    private function __construct($direction)
    {
        Assertion::inArray($direction, $this->allowedDirections);
        $this->direction = $direction;
    }

    /**
     * @return bool
     */
    public function isSource()
    {
        return $this->direction === self::DIRECTION_SOURCE;
    }

    /**
     * @return bool
     */
    public function isTarget()
    {
        return $this->direction === self::DIRECTION_TARGET;
    }

    /**
     * @return string
     */
    public function toString()
    {
        return $this->direction;
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return $this->toString();
    }

    /**
     * @param DataDirection $other
     * @return bool
     */
    public function equals(DataDirection $other)
    {
        return $this->toString() === $other->toString();
    }
} 