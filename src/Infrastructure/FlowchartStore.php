<?php
/*
 * This file is part of prooph/link.
 * (c) 2014-2015 prooph software GmbH <contact@prooph.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 * 
 * Date: 4/12/15 - 7:51 PM
 */
namespace Prooph\Link\ProcessManager\Infrastructure;
use Doctrine\DBAL\Connection;
use Prooph\Link\Application\Service\ApplicationDbAware;

/**
 * Class FlowchartStore
 *
 * Stores a flowchart ui configuration for a workflow
 *
 * @package Prooph\Link\ProcessManager\Infrastructure
 * @author Alexander Miertsch <kontakt@codeliner.ws>
 */
final class FlowchartStore implements ApplicationDbAware
{
    /**
     * @var Connection
     */
    private $connection;

    /**
     * @var string
     */
    private $flowchartConfigTable = "link_pm_flowchart_config";

    /**
     * @param string $workflowId
     * @param array $config
     */
    public function addFlowchartConfig($workflowId, array $config)
    {
        $this->connection->insert(
            $this->flowchartConfigTable,
            [
                'workflow_id' => $workflowId,
                'config' => json_encode($config),
                'last_updated_at' => (new \DateTime())->format(\DateTime::ISO8601),
            ]);
    }

    /**
     * @param string $workflowId
     * @param array $config
     */
    public function updateFlowchartConfig($workflowId, array $config)
    {
        $this->connection->update(
            $this->flowchartConfigTable,
            [
                'config' => json_encode($config),
                'last_updated_at' => (new \DateTime())->format(\DateTime::ISO8601),
            ],
            ['workflow_id' => $workflowId]
        );
    }

    /**
     * @param string $workflowId
     * @return array|null
     */
    public function getFlowchartConfig($workflowId)
    {
        $flowchartConfig = $this->connection->fetchAssoc("SELECT * FROM {$this->flowchartConfigTable} WHERE workflow_id = :id", ['id' => $workflowId]);

        if (! $flowchartConfig) {
            return null;
        }

        return [
            'workflow_id' => $flowchartConfig['workflow_id'],
            'config' => json_decode($flowchartConfig['config'], true),
            'last_updated_at' => $flowchartConfig['last_updated_at'],
        ];
    }

    /**
     * @param Connection $connection
     * @return void
     */
    public function setApplicationDb(Connection $connection)
    {
        $this->connection = $connection;
    }
}