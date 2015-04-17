<?php
/*
 * This file is part of prooph/link.
 * (c) 2014-2015 prooph software GmbH <contact@prooph.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 * 
 * Date: 4/17/15 - 6:53 PM
 */
namespace Prooph\Link\ProcessManager\Projection\Task;
use Doctrine\DBAL\Connection;
use Prooph\Link\Application\Service\ApplicationDbAware;
use Prooph\Link\ProcessManager\Projection\Tables;

/**
 * Class TaskFinder
 *
 * @package Prooph\Link\ProcessManager\Projection\Task
 * @author Alexander Miertsch <kontakt@codeliner.ws>
 */
final class TaskFinder implements ApplicationDbAware
{
    /**
     * @var Connection
     */
    private $connection;

    /**
     * @return array of task data
     */
    public function findAll()
    {
        $tasks = $this->connection->fetchAll('SELECT * FROM ' . Tables::TASK);

        foreach($tasks as &$task) {
            $this->fromDatabase($task);
        }

        return $tasks;
    }

    /**
     * @param $messageHandlerId
     * @return array of task data
     */
    public function findTasksOfMessageHandler($messageHandlerId)
    {
        $tasks = $this->connection->fetchAll('SELECT * FROM ' . Tables::TASK . ' WHERE message_handler_id = :mhid', ['mhid' => $messageHandlerId]);

        foreach($tasks as &$task) {
            $this->fromDatabase($task);
        }

        return $tasks;
    }

    /**
     * @param Connection $connection
     * @return mixed
     */
    public function setApplicationDb(Connection $connection)
    {
        $this->connection = $connection;
    }

    private function fromDatabase(array &$taskData)
    {
        $taskData['metadata'] = json_decode($taskData['metadata'], true);

        if(empty($taskData['metadata'])) {
            $taskData['metadata'] = new \ArrayObject();
        }
    }
}