<?php
/*
 * This file is part of prooph/link.
 * (c) 2014-2015 prooph software GmbH <contact@prooph.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 * 
 * Date: 4/4/15 - 1:34 AM
 */
namespace Prooph\Link\ProcessManager\Infrastructure;

use Prooph\EventSourcing\EventStoreIntegration\AggregateTranslator;
use Prooph\EventStore\Aggregate\AggregateRepository;
use Prooph\EventStore\Aggregate\AggregateType;
use Prooph\EventStore\EventStore;
use Prooph\EventStore\Stream\SingleStreamStrategy;
use Prooph\Link\ProcessManager\Model\Workflow\Workflow;
use Prooph\Link\ProcessManager\Model\Workflow\WorkflowCollection;
use Prooph\Link\ProcessManager\Model\Workflow\WorkflowId;

/**
 * Class PESWorkflowCollection
 *
 * @package Prooph\Link\ProcessManager\Infrastructure
 * @author Alexander Miertsch <kontakt@codeliner.ws>
 */
final class PESWorkflowCollection extends AggregateRepository implements WorkflowCollection
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
            AggregateType::fromAggregateRootClass('Prooph\Link\ProcessManager\Model\Workflow\Workflow')
        );
    }

    /**
     * @param WorkflowId $workflowId
     * @return Workflow
     */
    public function get(WorkflowId $workflowId)
    {
        return $this->getAggregateRoot($workflowId->toString());
    }

    /**
     * @param Workflow $workflow
     * @return void
     */
    public function add(Workflow $workflow)
    {
        $this->addAggregateRoot($workflow);
    }
}