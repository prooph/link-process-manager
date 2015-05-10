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

use Assert\Assertion;
use Prooph\Common\Messaging\Command;
use Prooph\Link\ProcessManager\Model\ProcessingMetadata;
use Prooph\Link\ProcessManager\Model\Task\TaskId;

/**
 * Command UpdateTaskMetadata
 *
 * @package Prooph\Link\ProcessManager\Command\Task
 * @author Alexander Miertsch <kontakt@codeliner.ws>
 */
final class UpdateTaskMetadata extends Command
{
    /**
     * @param array $taskMetadata
     * @param string $taskId
     * @return UpdateTaskMetadata
     */
    public static function to(array $taskMetadata, $taskId)
    {
        Assertion::uuid($taskId);

        return new self(
            __CLASS__,
            [
                'task_id' => $taskId,
                'task_metadata' => $taskMetadata
            ]
        );
    }

    /**
     * @return TaskId
     */
    public function taskId()
    {
        return TaskId::fromString($this->payload['task_id']);
    }

    /**
     * @return ProcessingMetadata
     */
    public function metadata()
    {
        return ProcessingMetadata::fromArray($this->payload['task_metadata']);
    }
}