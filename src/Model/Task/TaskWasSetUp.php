<?php
/*
 * This file is part of prooph/link.
 * (c) 2014-2015 prooph software GmbH <contact@prooph.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 * 
 * Date: 4/10/15 - 11:07 PM
 */
namespace Prooph\Link\ProcessManager\Model\Task;

use Prooph\EventSourcing\AggregateChanged;
use Prooph\Link\ProcessManager\Model\MessageHandler;
use Prooph\Link\ProcessManager\Model\ProcessingMetadata;
use Prooph\Processing\Type\Prototype;

/**
 * Class TaskWasSetUp
 *
 * @package Prooph\Link\ProcessManager\Model\Task
 * @author Alexander Miertsch <kontakt@codeliner.ws>
 */
final class TaskWasSetUp extends AggregateChanged
{
    private $taskId;

    private $taskType;

    private $messageHandlerId;

    private $processingType;

    private $taskMetadata;

    /**
     * @param TaskId $taskId
     * @param TaskType $taskType
     * @param MessageHandler $messageHandler
     * @param Prototype $processingType
     * @param ProcessingMetadata $metadata
     * @return TaskWasSetUp
     */
    public static function with(TaskId $taskId, TaskType $taskType, MessageHandler $messageHandler, Prototype $processingType,  ProcessingMetadata $metadata)
    {
        $event = self::occur($taskId->toString(), [
            'task_type' => $taskType->toString(),
            'message_handler_id' => $messageHandler->messageHandlerId()->toString(),
            'processing_type' => $processingType->of(),
            'metadata' => $metadata->toArray(),
        ]);

        $event->taskId = $taskId;
        $event->taskType = $taskType;
        $event->messageHandlerId = $messageHandler->messageHandlerId();
        $event->processingType = $processingType;
        $event->taskMetadata = $metadata;

        return $event;
    }

    /**
     * @return TaskId
     */
    public function taskId()
    {
        if (is_null($this->taskId)) {
            $this->taskId = TaskId::fromString($this->aggregateId());
        }

        return $this->taskId;
    }

    /**
     * @return TaskType
     */
    public function taskType()
    {
        if (is_null($this->taskType)) {
            $this->taskType = TaskType::fromString($this->payload['task_type']);
        }

        return $this->taskType;
    }

    /**
     * @return MessageHandler\MessageHandlerId
     */
    public function messageHandlerId()
    {
        if (is_null($this->messageHandlerId)) {
            $this->messageHandlerId = MessageHandler\MessageHandlerId::fromString($this->payload['message_handler_id']);
        }
        return $this->messageHandlerId;
    }

    /**
     * @return Prototype
     */
    public function processingType()
    {
        if (is_null($this->processingType)) {
            $type = $this->payload['processing_type'];
            $this->processingType = $type::prototype();
        }
        return $this->processingType;
    }

    /**
     * @return ProcessingMetadata
     */
    public function taskMetadata()
    {
        if (is_null($this->taskMetadata)) {
            $this->taskMetadata = ProcessingMetadata::fromArray($this->payload['metadata']);
        }
        return $this->taskMetadata;
    }
} 