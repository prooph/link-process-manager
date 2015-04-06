<?php
/*
 * This file is part of prooph/link.
 * (c) 2014-2015 prooph software GmbH <contact@prooph.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 * 
 * Date: 4/5/15 - 9:05 PM
 */
namespace ProophTest\Link\ProcessManager\Model;

use Prooph\Link\ProcessManager\Model\MessageHandler\MessageHandlerId;
use Prooph\Link\ProcessManager\Model\MessageHandler;
use ProophTest\Link\ProcessManager\TestCase;

final class MessageHandlerTest extends TestCase
{
    /**
     * @test
     */
    function it_creates_itself_with_a_message_handler_id_and_a_name()
    {
        $messageHandlerId = MessageHandlerId::generate();

        $messageHandler = MessageHandler::createWithName($messageHandlerId, 'Article Table');

        $this->assertTrue($messageHandlerId->equals($messageHandler->messageHandlerId()));
        $this->assertEquals('Article Table', $messageHandler->name());
    }
} 