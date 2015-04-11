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

    private $taskMetadata;

    /**
     * @param TaskId $taskId
     * @param TaskType $taskType
     * @param MessageHandler $messageHandler
     * @param ProcessingMetadata $metadata
     * @return TaskWasSetUp
     */
    public static function with(TaskId $taskId, TaskType $taskType, MessageHandler $messageHandler, ProcessingMetadata $metadata)
    {
        return self::occur($taskId->toString(), [
            'task_type' => $taskType->toString(),
            'message_handler_id' => $messageHandler->messageHandlerId()->toString(),
            'metadata' => $metadata->toArray(),
        ]);
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