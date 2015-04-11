<?php
/*
 * This file is part of prooph/link.
 * (c) 2014-2015 prooph software GmbH <contact@prooph.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 * 
 * Date: 4/6/15 - 11:47 PM
 */
namespace Prooph\Link\ProcessManager\Model\Workflow;

use Prooph\EventSourcing\AggregateChanged;
use Prooph\Link\ProcessManager\Model\ProcessingMetadata;
use Prooph\Link\ProcessManager\Model\Workflow;

/**
 * Event StartMessageWasAssignedToWorkflow
 *
 * @package Prooph\Link\ProcessManager\Model\Workflow
 * @author Alexander Miertsch <kontakt@codeliner.ws>
 */
final class StartMessageWasAssignedToWorkflow extends AggregateChanged
{
    private $workflowId;

    private $startMessage;

    /**
     * @param Message $startMessage
     * @param Workflow $workflow
     * @return StartMessageWasAssignedToWorkflow
     */
    public static function record(Message $startMessage, Workflow $workflow)
    {
        $event = self::occur($workflow->workflowId()->toString(), [
            'start_message' => [
                'message_type' => $startMessage->messageType()->toString(),
                'processing_type' => $startMessage->processingType()->of(),
                'metadata' => $startMessage->processingMetadata()->toArray()
            ]
        ]);

        $event->workflowId = $workflow->workflowId();
        $event->startMessage = $startMessage;

        return $event;
    }

    /**
     * @return WorkflowId
     */
    public function workflowId()
    {
        if (is_null($this->workflowId)) {
            $this->workflowId = WorkflowId::fromString($this->aggregateId());
        }
        return $this->workflowId;
    }

    /**
     * @return Message
     */
    public function startMessage()
    {
        if (is_null($this->startMessage)) {
            $processingType = $this->payload['start_message']['processing_type'];

            $this->startMessage = Message::emulateProcessingWorkflowMessage(
                MessageType::fromString($this->payload['start_message']['message_type']),
                $processingType::prototype(),
                ProcessingMetadata::fromArray($this->payload['start_message']['metadata'])
            );
        }
        return $this->startMessage;
    }
} 