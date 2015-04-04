<?php
/*
 * This file is part of prooph/link.
 * (c) 2014-2015 prooph software GmbH <contact@prooph.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 * 
 * Date: 4/4/15 - 4:39 PM
 */
namespace Prooph\Link\ProcessManager\Projection\Workflow;

use Doctrine\DBAL\Connection;
use Prooph\Link\Application\Service\ApplicationDbAware;
use Prooph\Link\ProcessManager\Model\Workflow\WorkflowWasCreated;

/**
 * Class WorkflowProjector
 *
 * @package Prooph\Link\ProcessManager\Projection\Workflow
 * @author Alexander Miertsch <kontakt@codeliner.ws>
 */
final class WorkflowProjector implements ApplicationDbAware
{
    /**
     * @var Connection
     */
    private $connection;

    /**
     * @var string
     */
    private $workflowTable = "link_pm_read_workflow";

    /**
     * @param WorkflowWasCreated $event
     */
    public function onWorkflowWasCreated(WorkflowWasCreated $event)
    {
        $this->connection->insert($this->workflowTable, [
            'uuid' => $event->workflowId()->toString(),
            'name' => $event->workflowName()
        ]);
    }

    /**
     * @param Connection $connection
     * @return mixed
     */
    public function setApplicationDb(Connection $connection)
    {
        $this->connection = $connection;
    }
}