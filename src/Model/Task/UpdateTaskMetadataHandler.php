<?php
/*
 * This file is part of prooph/link.
 * (c) 2014-2015 prooph software GmbH <contact@prooph.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 * 
 * Date: 4/17/15 - 10:09 PM
 */
namespace Prooph\Link\ProcessManager\Model\Task;
use Prooph\Link\ProcessManager\Command\Task\UpdateTaskMetadata;
use Prooph\Link\ProcessManager\Model\Task\Exception\TaskNotFound;

/**
 * Class UpdateTaskMetadataHandler
 *
 * @package Prooph\Link\ProcessManager\Model\Task
 * @author Alexander Miertsch <kontakt@codeliner.ws>
 */
final class UpdateTaskMetadataHandler 
{
    /**
     * @var TaskCollection
     */
    private $taskCollection;

    /**
     * @param TaskCollection $taskCollection
     */
    public function __construct(TaskCollection $taskCollection)
    {
        $this->taskCollection = $taskCollection;
    }

    /**
     * @param UpdateTaskMetadata $command
     * @throws Exception\TaskNotFound
     */
    public function handle(UpdateTaskMetadata $command)
    {
        $task = $this->taskCollection->get($command->taskId());

        if (is_null($task)) {
            throw TaskNotFound::withId($command->taskId());
        }

        $task->updateMetadata($command->metadata());
    }
} 