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
namespace Prooph\Link\ProcessManager\Model\MessageHandler;

use Rhumsaa\Uuid\Uuid;

/**
 * Class MessageHandlerId
 *
 * @package Prooph\Link\ProcessManager\Model\MessageHandler
 * @author Alexander Miertsch <kontakt@codeliner.ws>
 */
final class MessageHandlerId 
{
    /**
     * @var Uuid
     */
    private $uuid;

    /**
     * @return MessageHandlerId
     */
    public static function generate()
    {
        return new self(Uuid::uuid4());
    }

    /**
     * @param string $messageHandlerId
     * @return MessageHandlerId
     */
    public static function fromString($messageHandlerId)
    {
        return new self(Uuid::fromString($messageHandlerId));
    }

    private function __construct(Uuid $uuid)
    {
        $this->uuid = $uuid;
    }

    /**
     * @return string
     */
    public function toString()
    {
        return $this->uuid->toString();
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return $this->toString();
    }

    /**
     * @param MessageHandlerId $other
     * @return bool
     */
    public function equals(MessageHandlerId $other)
    {
        return $this->toString() === $other->toString();
    }
} 