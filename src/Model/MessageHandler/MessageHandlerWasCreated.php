<?php
/*
 * This file is part of prooph/link.
 * (c) 2014-2015 prooph software GmbH <contact@prooph.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 * 
 * Date: 4/5/15 - 8:52 PM
 */
namespace Prooph\Link\ProcessManager\Model\MessageHandler;

use Prooph\EventSourcing\AggregateChanged;

/**
 * Event MessageHandlerWasCreated
 *
 * @package Prooph\Link\ProcessManager\Model\MessageHandler
 * @author Alexander Miertsch <kontakt@codeliner.ws>
 */
final class MessageHandlerWasCreated extends AggregateChanged
{
    /**
     * @return MessageHandlerId
     */
    public function messageHandlerId()
    {
        return MessageHandlerId::fromString($this->aggregateId());
    }

    /**
     * @return string
     */
    public function messageHandlerName()
    {
        return $this->payload['name'];
    }
} 