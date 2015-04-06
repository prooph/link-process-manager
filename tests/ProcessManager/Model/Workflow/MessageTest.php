<?php
/*
 * This file is part of prooph/link.
 * (c) 2014-2015 prooph software GmbH <contact@prooph.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 * 
 * Date: 4/5/15 - 11:00 PM
 */
namespace ProophTest\Link\ProcessManager\Model\Workflow;

use Prooph\Link\ProcessManager\Model\Workflow\Message;
use Prooph\Link\ProcessManager\Model\Workflow\MessageType;
use Prooph\Processing\Type\String;
use ProophTest\Link\ProcessManager\TestCase;

final class MessageTest extends TestCase
{
    /**
     * @test
     */
    function it_emulates_a_processing_workflow_message()
    {
        $message = Message::emulateProcessingWorkflowMessage(MessageType::collectData(), String::prototype(), ['meta' => 'data']);

        $this->assertTrue(MessageType::collectData()->equals($message->messageType()));
        $this->assertEquals(String::prototype()->of(), $message->processingType()->of());
        $this->assertEquals(['meta' => 'data'], $message->processingMetadata());
    }

    /**
     * @test
     */
    function it_is_equal_to_a_similar_message()
    {
        $message1 = Message::emulateProcessingWorkflowMessage(MessageType::collectData(), String::prototype(), ['meta' => 'data']);
        $message2 = Message::emulateProcessingWorkflowMessage(MessageType::collectData(), String::prototype(), ['meta' => 'data']);

        $this->assertTrue($message1->equals($message2));
    }
} 