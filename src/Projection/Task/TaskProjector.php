<?php
/*
 * This file is part of prooph/link.
 * (c) 2014-2015 prooph software GmbH <contact@prooph.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 * 
 * Date: 4/16/15 - 10:05 PM
 */
namespace Prooph\Link\ProcessManager\Projection\Task;

use Doctrine\DBAL\Connection;
use Prooph\Link\Application\Service\ApplicationDbAware;
use Prooph\Link\ProcessManager\Model\Task\TaskId;
use Prooph\Link\ProcessManager\Model\Task\TaskWasSetUp;
use Prooph\Link\ProcessManager\Model\Workflow\TaskWasAddedToProcess;
use Prooph\Link\ProcessManager\Projection\Tables;

/**
 * Class TaskProjector
 *
 * @package Prooph\Link\ProcessManager\Projection\Task
 * @author Alexander Miertsch <kontakt@codeliner.ws>
 */
final class TaskProjector implements ApplicationDbAware
{
    /**
     * @var Connection
     */
    private $connection;

    private $tasksEvents = [];

    public function onTaskWasSetUp(TaskWasSetUp $event)
    {
        $this->addEventToTempList($event->taskId(), $event);
        $this->insertTaskIfDataIsComplete($event->taskId());
    }

    public function onTaskWasAddedToProcess(TaskWasAddedToProcess $event)
    {
        $this->addEventToTempList($event->taskId(), $event);
        $this->insertTaskIfDataIsComplete($event->taskId());
    }

    /**
     * @param Connection $connection
     * @return mixed
     */
    public function setApplicationDb(Connection $connection)
    {
        $this->connection = $connection;
    }

    /**
     * @param TaskId $taskId
     * @param mixed $event
     */
    private function addEventToTempList(TaskId $taskId, $event)
    {
        if (! isset($this->tasksEvents[$taskId->toString()])) {
            $this->tasksEvents[$taskId->toString()] = [];
        }

        $this->tasksEvents[$taskId->toString()][] = $event;
    }

    /**
     * @param TaskId $taskId
     */
    private function insertTaskIfDataIsComplete(TaskId $taskId)
    {
        if (isset($this->tasksEvents[$taskId->toString()]) && count($this->tasksEvents[$taskId->toString()]) >= 2) {
            $taskWasSetUp = null;
            $taskWasAddedToProcess = null;

            foreach ($this->tasksEvents[$taskId->toString()] as $event) {
                if ($event instanceof TaskWasSetUp) {
                    $taskWasSetUp = $event;
                    continue;
                }

                if ($event instanceof TaskWasAddedToProcess) {
                    $taskWasAddedToProcess = $event;
                    continue;
                }
            }

            if (!is_null($taskWasSetUp) && !is_null($taskWasAddedToProcess)) {
                $this->connection->insert(Tables::TASK, [
                    'id' => $taskWasSetUp->taskId()->toString(),
                    'type' => $taskWasSetUp->taskType()->toString(),
                    'metadata' => json_encode($taskWasSetUp->taskMetadata()->toArray()),
                    'workflow_id' => $taskWasAddedToProcess->workflowId()->toString(),
                    'process_id' => $taskWasAddedToProcess->processId()->toString(),
                    'message_handler_id' => $taskWasSetUp->messageHandlerId(),
                ]);
            }

            unset($this->tasksEvents[$taskId->toString()]);
        }
    }
}