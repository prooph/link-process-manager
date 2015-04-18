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

use Prooph\Link\Application\SharedKernel\MessageMetadata;
use Prooph\Link\ProcessManager\Model\MessageHandler\MessageHandlerId;
use Prooph\Link\ProcessManager\Model\MessageHandler;
use Prooph\Link\ProcessManager\Model\ProcessingMetadata;
use Prooph\Link\ProcessManager\Model\Task\TaskType;
use Prooph\Link\ProcessManager\Model\Task;
use Prooph\Link\ProcessManager\Model\Workflow\Message;
use Prooph\Link\ProcessManager\Model\Workflow\MessageType;
use Prooph\Processing\Processor\NodeName;
use ProophTest\Link\ProcessManager\Mock\ProcessingType\Article;
use ProophTest\Link\ProcessManager\Mock\ProcessingType\ArticleCollection;
use ProophTest\Link\ProcessManager\TestCase;

final class MessageHandlerTest extends TestCase
{
    /**
     * @test
     */
    function it_can_handle_a_collect_data_message_as_a_source()
    {
        $handler = $this->getMessageHandler(MessageHandler\DataDirection::DIRECTION_SOURCE);

        $message = Message::emulateProcessingWorkflowMessage(
            MessageType::collectData(),
            ArticleCollection::prototype(),
            ProcessingMetadata::noData()
        );

        $this->assertTrue($handler->canHandleMessage($message));
    }

    /**
     * @test
     */
    function it_can_not_handle_a_collect_data_message_as_target()
    {
        $handler = $this->getMessageHandler(MessageHandler\DataDirection::DIRECTION_TARGET);

        $message = Message::emulateProcessingWorkflowMessage(
            MessageType::collectData(),
            ArticleCollection::prototype(),
            ProcessingMetadata::noData()
        );

        $this->assertFalse($handler->canHandleMessage($message));
    }

    /**
     * @test
     */
    function it_can_handle_a_process_data_message_as_target()
    {
        $handler = $this->getMessageHandler(MessageHandler\DataDirection::DIRECTION_TARGET);

        $message = Message::emulateProcessingWorkflowMessage(
            MessageType::processData(),
            ArticleCollection::prototype(),
            ProcessingMetadata::noData()
        );

        $this->assertTrue($handler->canHandleMessage($message));
    }

    /**
     * @test
     */
    function it_can_not_handle_a_process_data_message_as_source()
    {
        $handler = $this->getMessageHandler(MessageHandler\DataDirection::DIRECTION_SOURCE);

        $message = Message::emulateProcessingWorkflowMessage(
            MessageType::processData(),
            ArticleCollection::prototype(),
            ProcessingMetadata::noData()
        );

        $this->assertFalse($handler->canHandleMessage($message));
    }

    /**
     * @test
     */
    function it_can_handle_a_process_data_message_as_script()
    {
        $handler = $this->getMessageHandler(MessageHandler\DataDirection::DIRECTION_TARGET, MessageHandler\HandlerType::TYPE_SCRIPT);

        $message = Message::emulateProcessingWorkflowMessage(
            MessageType::processData(),
            ArticleCollection::prototype(),
            ProcessingMetadata::noData()
        );

        $this->assertTrue($handler->canHandleMessage($message));
    }

    /**
     * @test
     */
    function it_can_not_handle_a_collect_data_message_as_script()
    {
        $handler = $this->getMessageHandler(MessageHandler\DataDirection::DIRECTION_TARGET, MessageHandler\HandlerType::TYPE_SCRIPT);

        $message = Message::emulateProcessingWorkflowMessage(
            MessageType::collectData(),
            ArticleCollection::prototype(),
            ProcessingMetadata::noData()
        );

        $this->assertFalse($handler->canHandleMessage($message));
    }

    /**
     * @test
     */
    function it_can_not_handle_a_message_with_a_limit_definition_without_chunk_support()
    {
        $handler = $this->getMessageHandler(MessageHandler\DataDirection::DIRECTION_TARGET);

        $message = Message::emulateProcessingWorkflowMessage(
            MessageType::processData(),
            ArticleCollection::prototype(),
            ProcessingMetadata::fromArray([MessageMetadata::LIMIT => 100])
        );

        $this->assertFalse($handler->canHandleMessage($message));
    }

    /**
     * @test
     */
    function it_can_handle_a_message_with_a_limit_definition_because_it_has_chunk_support()
    {
        $handler = $this->getMessageHandler(
            MessageHandler\DataDirection::DIRECTION_TARGET,
            MessageHandler\HandlerType::TYPE_CONNECTOR,
            ['chunk_support' => true]
        );

        $message = Message::emulateProcessingWorkflowMessage(
            MessageType::processData(),
            ArticleCollection::prototype(),
            ProcessingMetadata::fromArray([MessageMetadata::LIMIT => 100])
        );

        $this->assertTrue($handler->canHandleMessage($message));
    }

    /**
     * @test
     * @dataProvider provideMessages
     */
    function it_can_handle_all_message_types_as_a_callback(Message $message)
    {
        $handler = $this->getMessageHandler(MessageHandler\DataDirection::DIRECTION_TARGET, MessageHandler\HandlerType::TYPE_CALLBACK);

        $this->assertTrue($handler->canHandleMessage($message));
    }

    function provideMessages()
    {
        return [
            [
                Message::emulateProcessingWorkflowMessage(
                    MessageType::collectData(),
                    ArticleCollection::prototype(),
                    ProcessingMetadata::noData()
                )
            ],
            [
                Message::emulateProcessingWorkflowMessage(
                    MessageType::dataCollected(),
                    ArticleCollection::prototype(),
                    ProcessingMetadata::noData()
                )
            ],
            [
                Message::emulateProcessingWorkflowMessage(
                    MessageType::processData(),
                    ArticleCollection::prototype(),
                    ProcessingMetadata::noData()
                )
            ],
            [
                Message::emulateProcessingWorkflowMessage(
                    MessageType::dataProcessed(),
                    ArticleCollection::prototype(),
                    ProcessingMetadata::noData()
                )
            ],
        ];
    }

    /**
     * @test
     */
    function it_returns_a_emulated_data_collected_answer_when_it_is_a_source_connector()
    {
        $messageHandler = $this->getMessageHandler(MessageHandler\DataDirection::DIRECTION_SOURCE, MessageHandler\HandlerType::TYPE_CONNECTOR, ['chunk_support' => true]);

        $connectedTask = Task::setUp($messageHandler, TaskType::collectData(), ArticleCollection::prototype(), ProcessingMetadata::fromArray([MessageMetadata::LIMIT => 100]));

        $answer = $messageHandler->emulateAnswerMessage($connectedTask);

        $this->assertInstanceOf(Message::class, $answer);

        $this->assertTrue($answer->messageType()->isDataCollectedMessage());
        $this->assertEquals($connectedTask->processingType()->of(), $answer->processingType()->of());
        $this->assertEquals($connectedTask->metadata()->toArray(), $answer->processingMetadata()->toArray());
    }

    /**
     * @test
     */
    function it_returns_a_emulated_data_processed_answer_when_it_is_target_connector()
    {
        $messageHandler = $this->getMessageHandler(MessageHandler\DataDirection::DIRECTION_TARGET, MessageHandler\HandlerType::TYPE_CONNECTOR, ['chunk_support' => true]);

        $connectedTask = Task::setUp($messageHandler, TaskType::processData(), ArticleCollection::prototype(), ProcessingMetadata::fromArray([MessageMetadata::LIMIT => 100]));

        $answer = $messageHandler->emulateAnswerMessage($connectedTask);

        $this->assertInstanceOf(Message::class, $answer);

        $this->assertTrue($answer->messageType()->isDataProcessedMessage());
        $this->assertEquals($connectedTask->processingType()->of(), $answer->processingType()->of());
        $this->assertEquals($connectedTask->metadata()->toArray(), $answer->processingMetadata()->toArray());
    }

    /**
     * @param $dataDirection
     * @param null|string $handlerType
     * @param null|array $metadata
     * @return MessageHandler
     */
    private function getMessageHandler($dataDirection, $handlerType = null, array $metadata = null)
    {
        if (! is_null($handlerType)) {
            $handlerType = MessageHandler\HandlerType::fromString($handlerType);
        } else {
            $handlerType = MessageHandler\HandlerType::connector();
        }

        if (! is_null($metadata)) {
            $metadata = ProcessingMetadata::fromArray($metadata);;
        } else {
            $metadata = ProcessingMetadata::noData();
        }

        return MessageHandler::fromDefinition(
            MessageHandlerId::generate(),
            'Article Export',
            NodeName::defaultName(),
            $handlerType,
            MessageHandler\DataDirection::fromString($dataDirection),
            MessageHandler\ProcessingTypes::support([ArticleCollection::prototype(), Article::prototype()]),
            $metadata,
            'sqlconnector-pm-metadata',
            'glyphicon-hdd',
            'glyphicon',
            Article::prototype(),
            MessageHandler\ProcessingId::fromString('sqlconnector:::example')
        );
    }
} 