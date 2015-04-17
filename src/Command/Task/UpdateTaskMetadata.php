<?php
/*
 * This file is part of prooph/link.
 * (c) 2014-2015 prooph software GmbH <contact@prooph.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 * 
 * Date: 4/17/15 - 9:59 PM
 */
namespace Prooph\Link\ProcessManager\Command\Task;
use Prooph\Link\Application\Service\TransactionCommand;
use Prooph\Link\Application\Service\TransactionId;
use Prooph\Link\Application\Service\TransactionIdGenerator;
use Prooph\Link\ProcessManager\Model\ProcessingMetadata;
use Prooph\Link\ProcessManager\Model\Task\TaskId;
use Prooph\ServiceBus\Message\MessageNameProvider;

/**
 * Command UpdateTaskMetadata
 *
 * @package Prooph\Link\ProcessManager\Command\Task
 * @author Alexander Miertsch <kontakt@codeliner.ws>
 */
final class UpdateTaskMetadata implements TransactionCommand, MessageNameProvider
{
    use TransactionIdGenerator;

    /**
     * @var TaskId
     */
    private $taskId;

    /**
     * @var ProcessingMetadata
     */
    private $taskMetadata;

    public function __construct($taskId, $taskMetadata)
    {
        if (! $taskId instanceof TaskId) {
            $taskId = TaskId::fromString($taskId);
        }

        if (! $taskMetadata instanceof ProcessingMetadata) {
            $taskMetadata = ProcessingMetadata::fromArray($taskMetadata);
        }

        $this->taskId = $taskId;
        $this->taskMetadata = $taskMetadata;
    }

    /**
     * @return TaskId
     */
    public function taskId()
    {
        return $this->taskId;
    }

    /**
     * @return ProcessingMetadata
     */
    public function metadata()
    {
        return $this->taskMetadata;
    }

    /**
     * @return string Name of the message
     */
    public function getMessageName()
    {
        return __CLASS__;
    }
}