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
use Prooph\Common\Messaging\Command;
use Prooph\Link\ProcessManager\Model\MessageHandler\MessageHandlerId;
use Prooph\Link\ProcessManager\Model\ProcessingMetadata;
use Prooph\Link\ProcessManager\Model\Workflow\Message;
use Prooph\Link\ProcessManager\Model\Workflow\MessageType;
use Prooph\Link\ProcessManager\Model\Workflow\WorkflowId;
use Prooph\Processing\Type\Type;

/**
 * Command ScheduleFirstTasksForWorkflow
 *
 * Given a start message and the first message handler the corresponding Workflow should determine the first tasks to process
 * the start message with the message handler.
 *
 * @package Prooph\Link\ProcessManager\Command\Workflow
 * @author Alexander Miertsch <kontakt@codeliner.ws>
 */
final class ScheduleFirstTasksForWorkflow extends Command
{
    /**
     * @param string $workflowId
     * @param array $startMessage
     * @param string $firstMessageHandlerId
     * @return ScheduleFirstTasksForWorkflow
     */
    public static function withData($workflowId, $startMessage, $firstMessageHandlerId)
    {
        Assertion::uuid($workflowId);
        Assertion::uuid($firstMessageHandlerId);
        Assertion::isArray($startMessage);
        Assertion::keyExists($startMessage, 'message_type');
        Assertion::keyExists($startMessage, 'processing_type');

        $processingType = $startMessage['processing_type'];

        Assertion::implementsInterface($processingType, Type::class);

        return new self(
            __CLASS__,
            [
                'workflow_id' => $workflowId,
                'start_message' => $startMessage,
                'first_message_handler' => $firstMessageHandlerId
            ]
        );
    }

    /**
     * @return WorkflowId
     */
    public function workflowId()
    {
        return WorkflowId::fromString($this->payload['workflow_id']);
    }

    /**
     * @return Message
     */
    public function startMessage()
    {
        $startMessage = $this->payload['start_message'];

        if (isset($startMessage['metadata'])) {
            $metadata = ProcessingMetadata::fromArray($startMessage['metadata']);
        } else {
            $metadata = ProcessingMetadata::noData();
        }

        $processingType = $startMessage['processing_type'];

        return Message::emulateProcessingWorkflowMessage(
            MessageType::fromString($startMessage['message_type']),
            $processingType::prototype(),
            $metadata
        );
    }

    /**
     * @return MessageHandlerId
     */
    public function firstMessageHandlerId()
    {
        return MessageHandlerId::fromString($this->payload['first_message_handler']);
    }
}