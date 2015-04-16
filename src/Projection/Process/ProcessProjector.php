<?php
/*
 * This file is part of prooph/link.
 * (c) 2014-2015 prooph software GmbH <contact@prooph.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 * 
 * Date: 4/16/15 - 10:56 PM
 */
namespace Prooph\Link\ProcessManager\Projection\Process;

use Doctrine\DBAL\Connection;
use Prooph\Link\Application\Service\ApplicationDbAware;
use Prooph\Link\ProcessManager\Model\Workflow\ProcessWasAddedToWorkflow;
use Prooph\Link\ProcessManager\Projection\Tables;

/**
 * Class ProcessProjector
 *
 * @package Prooph\Link\ProcessManager\Projection\Process
 * @author Alexander Miertsch <kontakt@codeliner.ws>
 */
final class ProcessProjector implements ApplicationDbAware
{
    /**
     * @var Connection
     */
    private $connection;

    public function onProcessWasAddedToWorkflow(ProcessWasAddedToWorkflow $event)
    {
        $this->connection->insert(Tables::PROCESS, [
            'id' => $event->processId()->toString(),
            'type' => $event->processType()->toString(),
            'workflow_id' => $event->workflowId()->toString(),
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