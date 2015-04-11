<?php
/*
 * This file is part of prooph/link.
 * (c) 2014-2015 prooph software GmbH <contact@prooph.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 * 
 * Date: 4/6/15 - 4:57 PM
 */
namespace Prooph\Link\ProcessManager\Model;

use Prooph\EventSourcing\AggregateRoot;
use Prooph\Link\ProcessManager\Model\MessageHandler\MessageHandlerId;
use Prooph\Link\ProcessManager\Model\Task\TaskId;
use Prooph\Link\ProcessManager\Model\Task\TaskType;
use Prooph\Link\ProcessManager\Model\Task\TaskWasSetUp;

/**
 * Class Task
 *
 * In the process manager domain a task is an own aggregate because it needs to be modified by the client independent of
 * its process. But the task keeps a reference to the process so that it can be assigned to it when the whole workflow
 * is ported to the processing configuration.
 *
 * @package Prooph\Link\ProcessManager\Model
 * @author Alexander Miertsch <kontakt@codeliner.ws>
 */
final class Task extends AggregateRoot
{
    /**
     * @var TaskId
     */
    private $id;

    /**
     * @var TaskType
     */
    private $taskType;

    /**
     * @var MessageHandlerId
     */
    private $messageHandlerId;

    /**
     * @var ProcessingMetadata
     */
    private $processingMetadata;

    /**
     * @param MessageHandler $messageHandler
     * @param TaskType $taskType
     * @param ProcessingMetadata $metadata
     * @return Task
     */
    public static function setUp(MessageHandler $messageHandler, TaskType $taskType, ProcessingMetadata $metadata)
    {
        $instance = new self();

        $instance->recordThat(TaskWasSetUp::with(TaskId::generate(), $taskType, $messageHandler, $metadata));

        return $instance;
    }

    /**
     * @return TaskId
     */
    public function id()
    {
        return $this->id;
    }

    /**
     * @return TaskType
     */
    public function type()
    {
        return $this->taskType;
    }

    /**
     * @return MessageHandlerId
     */
    public function messageHandlerId()
    {
        return $this->messageHandlerId;
    }

    /**
     * @return ProcessingMetadata
     */
    public function metadata()
    {
        return $this->processingMetadata;
    }

    /**
     * @return string representation of the unique identifier of the aggregate root
     */
    protected function aggregateId()
    {
        return $this->id->toString();
    }

    /**
     * @param TaskWasSetUp $event
     */
    protected function whenTaskWasSetUp(TaskWasSetUp $event)
    {
        $this->id = $event->taskId();
        $this->taskType = $event->taskType();
        $this->messageHandlerId = $event->messageHandlerId();
        $this->processingMetadata = $event->taskMetadata();
    }
}