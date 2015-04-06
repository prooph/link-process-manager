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

use Prooph\Link\Application\DataType\SqlConnector\Ifair\ArticlesCollection;
use Prooph\Link\ProcessManager\Model\MessageHandler\MessageHandlerId;
use Prooph\Link\ProcessManager\Model\MessageHandler;
use Prooph\Link\ProcessManager\Model\ProcessingMetadata;
use Prooph\Link\ProcessManager\Model\Workflow\Message;
use Prooph\Link\ProcessManager\Model\Workflow\MessageType;
use Prooph\Processing\Processor\NodeName;
use Prooph\Processing\Type\String;
use ProophTest\Link\ProcessManager\Mock\ProcessingType\Article;
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
            ArticlesCollection::prototype(),
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
            ArticlesCollection::prototype(),
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
            ArticlesCollection::prototype(),
            ProcessingMetadata::noData()
        );

        $this->assertTrue($handler->canHandleMessage($message));
    }

    /**
     * @test
     */
    function it_can_not_hanlde_a_process_data_message_as_source()
    {
        $handler = $this->getMessageHandler(MessageHandler\DataDirection::DIRECTION_SOURCE);

        $message = Message::emulateProcessingWorkflowMessage(
            MessageType::processData(),
            ArticlesCollection::prototype(),
            ProcessingMetadata::noData()
        );

        $this->assertFalse($handler->canHandleMessage($message));
    }

    /**
     * @param $dataDirection
     * @return MessageHandler
     */
    private function getMessageHandler($dataDirection)
    {
        return MessageHandler::fromDefinition(
            MessageHandlerId::generate(),
            'Article Export',
            NodeName::defaultName(),
            MessageHandler\HandlerType::connector(),
            MessageHandler\DataDirection::fromString($dataDirection),
            MessageHandler\ProcessingTypes::support([ArticlesCollection::prototype(), Article::prototype()]),
            ProcessingMetadata::fromArray(['limit' => 100]),
            Article::prototype(),
            MessageHandler\ProcessingId::fromString('sqlconnector:::example')
        );
    }
} 