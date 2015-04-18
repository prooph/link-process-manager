<?php
/*
 * This file is part of prooph/link.
 * (c) 2014-2015 prooph software GmbH <contact@prooph.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 * 
 * Date: 4/16/15 - 6:21 PM
 */
namespace Prooph\Link\ProcessManager\Command\Workflow;

use Assert\Assertion;
use Prooph\Link\Application\Service\TransactionCommand;
use Prooph\Link\Application\Service\TransactionIdGenerator;
use Prooph\Link\ProcessManager\Model\MessageHandler\MessageHandlerId;
use Prooph\Link\ProcessManager\Model\ProcessingMetadata;
use Prooph\Link\ProcessManager\Model\Workflow\Message;
use Prooph\Link\ProcessManager\Model\Workflow\MessageType;
use Prooph\Link\ProcessManager\Model\Workflow\WorkflowId;
use Prooph\Processing\Type\Type;
use Prooph\ServiceBus\Message\MessageNameProvider;

/**
 * Command ScheduleFirstTasksForWorkflow
 *
 * Given a start message and the first message handler the corresponding Workflow should determine the first tasks to process
 * the start message with the message handler.
 *
 * @package Prooph\Link\ProcessManager\Command\Workflow
 * @author Alexander Miertsch <kontakt@codeliner.ws>
 */
final class ScheduleFirstTasksForWorkflow implements TransactionCommand, MessageNameProvider
{
    use TransactionIdGenerator;

    private $workflowId;

    private $startMessage;

    private $messageHandlerId;

    /**
     * @param string|WorkflowId $workflowId
     * @param array|Message $startMessage
     * @param string|MessageHandlerId $firstMessageHandlerId
     */
    public function __construct($workflowId, $startMessage, $firstMessageHandlerId)
    {
        if (! $workflowId instanceof WorkflowId) {
            $workflowId = WorkflowId::fromString($workflowId);
        }

        if (! $startMessage instanceof Message) {
            Assertion::keyExists($startMessage, 'message_type');
            Assertion::keyExists($startMessage, 'processing_type');

            $processingType = $startMessage['processing_type'];

            Assertion::implementsInterface($processingType, Type::class);

            if (isset($startMessage['metadata'])) {
                $metadata = ProcessingMetadata::fromArray($startMessage['metadata']);
            } else {
                $metadata = ProcessingMetadata::noData();
            }

            $startMessage = Message::emulateProcessingWorkflowMessage(
                MessageType::fromString($startMessage['message_type']),
                $processingType::prototype(),
                $metadata
            );
        }

        if (! $firstMessageHandlerId instanceof MessageHandlerId) {
            $firstMessageHandlerId = MessageHandlerId::fromString($firstMessageHandlerId);
        }

        $this->workflowId = $workflowId;
        $this->startMessage = $startMessage;
        $this->messageHandlerId = $firstMessageHandlerId;
    }

    /**
     * @return WorkflowId
     */
    public function workflowId()
    {
        return $this->workflowId;
    }

    /**
     * @return Message
     */
    public function startMessage()
    {
        return $this->startMessage;
    }

    /**
     * @return MessageHandlerId
     */
    public function firstMessageHandlerId()
    {
        return $this->messageHandlerId;
    }

    /**
     * @return string Name of the message
     */
    public function getMessageName()
    {
        return __CLASS__;
    }
}