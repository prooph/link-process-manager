<?php
/*
 * This file is part of prooph/link.
 * (c) 2014-2015 prooph software GmbH <contact@prooph.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 * 
 * Date: 5/24/15 - 11:58 PM
 */
namespace Prooph\Link\ProcessManager\Projection\Log;

use Assert\Assertion;
use Doctrine\DBAL\Connection;
use Prooph\Link\Application\Service\ApplicationDbAware;
use Prooph\Link\ProcessManager\Projection\Tables;

/**
 * Class ProcessLogFinder
 *
 * @package Prooph\Link\ProcessManager\Projection\Log
 * @author Alexander Miertsch <kontakt@codeliner.ws>
 */
final class ProcessLogFinder implements ApplicationDbAware
{
    /**
     * @var Connection
     */
    private $connection;

    /**
     * Orders process logs by started_at DESC
     * Returns array of process log entry arrays.
     * Each process log contains the information:
     *
     * - process_id => UUID string
     * - status => running|succeed|failed
     * - start_message => string|null
     * - started_at => \DateTime::ISO8601 formatted
     * - finished_at =>  \DateTime::ISO8601 formatted
     *
     * @param int $offset
     * @param int $limit
     * @return array
     */
    public function getLastLoggedProcesses($offset = 0, $limit = 10)
    {
        Assertion::integer($offset);
        Assertion::integer($limit);

        $query = $this->connection->createQueryBuilder();

        $query->select('*')->from(Tables::PROCESS_LOG)->orderBy('started_at', 'DESC')->setFirstResult($offset)->setMaxResults($limit);

        return $query->execute()->fetchAll();
    }

    /**
     * @param string $processId
     * @return null|array process log, see {@method getLastLoggedProcesses} for structure
     */
    public function getLoggedProcess($processId)
    {
        Assertion::uuid($processId);

        $query = $this->connection->createQueryBuilder();

        $query->select('*')->from(Tables::PROCESS_LOG)
            ->where('process_id = :process_id')
            ->setParameter('process_id', $processId);

        return $query->execute()->fetch();
    }

    /**
     * Get logs triggered by given start message
     * Orders the logs by started_at DESC
     *
     * @param string $startMessage
     * @return array
     */
    public function getLogsTriggeredBy($startMessage)
    {
        $query = $this->connection->createQueryBuilder();

        $query->select('*')->from(Tables::PROCESS_LOG)
            ->where('start_message = :start_message')
            ->orderBy('started_at', 'DESC')
            ->setParameter('start_message', $startMessage);

        return $query->execute()->fetchAll();
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