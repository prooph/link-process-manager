<?php
/*
 * This file is part of prooph/link.
 * (c) 2014-2015 prooph software GmbH <contact@prooph.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 * 
 * Date: 4/17/15 - 10:28 PM
 */
namespace Prooph\Link\ProcessManager\Model\Task;

use Prooph\EventSourcing\AggregateChanged;
use Prooph\Link\Application\Service\TransactionEvent;
use Prooph\Link\Application\Service\TransactionIdAware;
use Prooph\Link\ProcessManager\Model\ProcessingMetadata;

/**
 * Event TaskMetadataWasUpdated
 *
 * @package Prooph\Link\ProcessManager\Model\Task
 * @author Alexander Miertsch <kontakt@codeliner.ws>
 */
final class TaskMetadataWasUpdated extends AggregateChanged
{
    private $taskId;

    private $taskMetadata;

    public static function record(TaskId $taskId, ProcessingMetadata $metadata)
    {
        $event = self::occur($taskId->toString(), ['task_metadata' => $metadata->toArray()]);

        $event->taskId = $taskId;
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
     * @return ProcessingMetadata
     */
    public function taskMetadata()
    {
        if (is_null($this->taskMetadata)) {
            $this->taskMetadata = ProcessingMetadata::fromArray($this->payload['task_metadata']);
        }
        return $this->taskMetadata;
    }
}