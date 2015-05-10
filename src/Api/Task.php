<?php
/*
 * This file is part of prooph/link.
 * (c) 2014-2015 prooph software GmbH <contact@prooph.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 * 
 * Date: 4/17/15 - 6:51 PM
 */
namespace Prooph\Link\ProcessManager\Api;

use Prooph\Link\Application\Service\AbstractRestController;
use Prooph\Link\Application\Service\ActionController;
use Prooph\Link\ProcessManager\Command\Task\UpdateTaskMetadata;
use Prooph\Link\ProcessManager\Projection\Task\TaskFinder;
use Prooph\ServiceBus\CommandBus;

/**
 * Resource Task
 *
 * @package Prooph\Link\ProcessManager\Api
 * @author Alexander Miertsch <kontakt@codeliner.ws>
 */
final class Task extends AbstractRestController implements ActionController
{
    /**
     * @var CommandBus
     */
    private $commandBus;

    /**
     * @var TaskFinder
     */
    private $taskFinder;

    public function getList()
    {
        $messageHandlerId = $this->getRequest()->getQuery('message_handler_id');

        if ($messageHandlerId) {
            $taskCollection = $this->taskFinder->findTasksOfMessageHandler($messageHandlerId);
        } else {
            $taskCollection = $this->taskFinder->findAll();
        }

        return ['task_collection' => $taskCollection];
    }

    public function update($id, $data)
    {
        if (! array_key_exists('metadata', $data)) return $this->apiProblem(422, "No metadata given for the task");

        $this->commandBus->dispatch(UpdateTaskMetadata::to($data['metadata'], $id));

        return $this->accepted();
    }

    /**
     * @param CommandBus $commandBus
     * @return void
     */
    public function setCommandBus(CommandBus $commandBus)
    {
        $this->commandBus = $commandBus;
    }

    /**
     * @param TaskFinder $taskFinder
     */
    public function setTaskFinder(TaskFinder $taskFinder)
    {
        $this->taskFinder = $taskFinder;
    }
}