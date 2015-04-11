<?php
/*
 * This file is part of prooph/link.
 * (c) 2014-2015 prooph software GmbH <contact@prooph.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 * 
 * Date: 4/6/15 - 10:56 PM
 */
namespace Prooph\Link\ProcessManager\Model\Workflow\Exception;

use Prooph\Link\ProcessManager\Model\Error\ClientError;
use Prooph\Link\ProcessManager\Model\MessageHandler;
use Prooph\Link\ProcessManager\Model\Workflow\Message;

/**
 * Exception MessageIsNotManageable
 *
 * This exception is thrown by the Workflow aggregate if a Message can not be handled
 *
 * @package Prooph\Link\ProcessManager\Model\Workflow\Exception
 * @author Alexander Miertsch <alexander.miertsch.extern@sixt.com>
 */
final class MessageIsNotManageable extends \RuntimeException implements ClientError
{
    /**
     * @param MessageHandler $messageHandler
     * @param Message $message
     * @return MessageIsNotManageable
     */
    public static function byMessageHandler(MessageHandler $messageHandler, Message $message)
    {
        $message = sprintf(
            "Message %s -> %s is not manageable by message handler %s: %s",
            $message->messageType()->toString(),
            $message->processingType()->typeDescription()->label(),
            $messageHandler->name(),
            $messageHandler->lastValidationError()
        );

        return new self($message);
    }
} 