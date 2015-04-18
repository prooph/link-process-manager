<?php
/*
 * This file is part of prooph/link.
 * (c) 2014-2015 prooph software GmbH <contact@prooph.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 * 
 * Date: 4/11/15 - 4:22 PM
 */
namespace ProophTest\Link\ProcessManager\Model;

use Prooph\Link\Application\SharedKernel\MessageMetadata;
use Prooph\Link\ProcessManager\Model\MessageHandler;
use Prooph\Link\ProcessManager\Model\ProcessingMetadata;
use Prooph\Link\ProcessManager\Model\Task;
use Prooph\Link\ProcessManager\Model\Workflow\Message;
use ProophTest\Link\ProcessManager\Mock\ProcessingType\ArticleCollection;
use ProophTest\Link\ProcessManager\TestCase;

/**
 * Class TaskTest
 *
 * @package ProophTest\Link\ProcessManager
 * @author Alexander Miertsch <alexander.miertsch.extern@sixt.com>
 */
final class TaskTest extends TestCase
{
    /**
     * @test
     */
    function it_is_set_up_with_a_type_a_message_handler_and_metadata()
    {
        $messageHandler = $this->getArticleExporterMessageHandler();

        $task = Task::setUp($messageHandler, Task\TaskType::collectData(), ArticleCollection::prototype(), ProcessingMetadata::fromArray([MessageMetadata::LIMIT => 100]));

        $this->assertTrue(Task\TaskType::collectData()->equals($task->type()));
        $this->assertTrue($messageHandler->messageHandlerId()->equals($task->messageHandlerId()));
        $this->assertEquals([MessageMetadata::LIMIT => 100], $task->metadata()->toArray());
    }

    /**
     * @test
     */
    function it_returns_a_emulated_collect_data_message_when_its_type_is_collect_data()
    {
        $messageHandler = $this->getArticleExporterMessageHandler();

        $task = Task::setUp($messageHandler, Task\TaskType::collectData(), ArticleCollection::prototype(), ProcessingMetadata::fromArray([MessageMetadata::LIMIT => 100]));

        $message = $task->emulateWorkflowMessage();

        $this->assertInstanceOf(Message::class, $message);

        $this->assertTrue($message->messageType()->isCollectDataMessage());
        $this->assertEquals($task->processingType()->of(), $message->processingType()->of());
        $this->assertEquals($task->metadata()->toArray(), $message->processingMetadata()->toArray());
    }

    /**
     * @test
     */
    function it_returns_a_emulated_process_data_message_when_its_type_is_process_data()
    {
        $messageHandler = $this->getArticleImporterMessageHandler();

        $task = Task::setUp($messageHandler, Task\TaskType::processData(), ArticleCollection::prototype(), ProcessingMetadata::fromArray([MessageMetadata::LIMIT => 100]));

        $message = $task->emulateWorkflowMessage();

        $this->assertInstanceOf(Message::class, $message);

        $this->assertTrue($message->messageType()->isProcessDataMessage());
        $this->assertEquals($task->processingType()->of(), $message->processingType()->of());
        $this->assertEquals($task->metadata()->toArray(), $message->processingMetadata()->toArray());
    }
} 