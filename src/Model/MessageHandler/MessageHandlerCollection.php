<?php
/*
 * This file is part of prooph/link.
 * (c) 2014-2015 prooph software GmbH <contact@prooph.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 * 
 * Date: 4/11/15 - 9:50 PM
 */

namespace Prooph\Link\ProcessManager\Model\MessageHandler;

use Prooph\Link\ProcessManager\Model\MessageHandler;

/**
 * Interface MessageHandlerCollection
 *
 * @package Prooph\Link\ProcessManager\Model\MessageHandler
 * @author Alexander Miertsch <kontakt@codeliner.ws>
 */
interface MessageHandlerCollection 
{
    /**
     * @param MessageHandler $messageHandler
     * @return void
     */
    public function add(MessageHandler $messageHandler);

    /**
     * @param MessageHandlerId $handlerId
     * @return MessageHandler
     */
    public function get(MessageHandlerId $handlerId);
} 