<?php
/*
 * This file is part of prooph/link.
 * (c) 2014-2015 prooph software GmbH <contact@prooph.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 * 
 * Date: 4/16/15 - 6:47 PM
 */
namespace Prooph\Link\ProcessManager\Model\MessageHandler\Exception;

use Prooph\Link\ProcessManager\Model\MessageHandler\MessageHandlerId;

/**
 * Exception MessageHandlerNotFound
 *
 * @package Prooph\Link\ProcessManager\Model\MessageHandler\Esception
 * @author Alexander Miertsch <kontakt@codeliner.ws>
 */
final class MessageHandlerNotFound extends \InvalidArgumentException
{
    /**
     * @param MessageHandlerId $messageHandlerId
     * @return MessageHandlerNotFound
     */
    public static function withId(MessageHandlerId $messageHandlerId)
    {
        return new self(sprintf('Message Handler with id %s could not be found', $messageHandlerId->toString()));
    }
} 