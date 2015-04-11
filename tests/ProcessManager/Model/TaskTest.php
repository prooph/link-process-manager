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

        $task = Task::setUp($messageHandler, Task\TaskType::collectData(), ProcessingMetadata::fromArray([MessageMetadata::LIMIT => 100]));

        $this->assertTrue(Task\TaskType::collectData()->equals($task->type()));
        $this->assertTrue($messageHandler->messageHandlerId()->equals($task->messageHandlerId()));
        $this->assertEquals([MessageMetadata::LIMIT => 100], $task->metadata()->toArray());
    }
} 