<?php
/*
 * This file is part of prooph/link.
 * (c) 2014-2015 prooph software GmbH <contact@prooph.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 * 
 * Date: 4/16/15 - 6:39 PM
 */
namespace Prooph\Link\ProcessManager\Infrastructure;

use Prooph\EventSourcing\EventStoreIntegration\AggregateTranslator;
use Prooph\EventStore\Aggregate\AggregateRepository;
use Prooph\EventStore\Aggregate\AggregateType;
use Prooph\EventStore\EventStore;
use Prooph\EventStore\Stream\SingleStreamStrategy;
use Prooph\Link\ProcessManager\Model\Task;
use Prooph\Link\ProcessManager\Model\Task\TaskId;

/**
 * Class PESTaskCollection
 *
 * @package Prooph\Link\ProcessManager\Infrastructure
 * @author Alexander Miertsch <kontakt@codeliner.ws>
 */
final class PESTaskCollection extends AggregateRepository implements Task\TaskCollection
{
    /**
     * @param EventStore $eventStore
     */
    public function __construct(
        EventStore $eventStore
    )
    {
        parent::__construct(
            $eventStore,
            new AggregateTranslator(),
            new SingleStreamStrategy($eventStore, 'link_process_manager_stream'),
            AggregateType::fromAggregateRootClass(Task::class)
        );
    }

    /**
     * @param TaskId $taskId
     * @return Task
     */
    public function get(TaskId $taskId)
    {
        return $this->getAggregateRoot($taskId->toString());
    }

    /**
     * @param Task $task
     * @return void
     */
    public function add(Task $task)
    {
       $this->addAggregateRoot($task);
    }
}