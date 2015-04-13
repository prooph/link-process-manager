<?php
/*
 * This file is part of prooph/link.
 * (c) 2014-2015 prooph software GmbH <contact@prooph.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 * 
 * Date: 4/13/15 - 8:02 PM
 */
namespace Prooph\Link\ProcessManager\Projection\MessageHandler;

use Doctrine\DBAL\Connection;
use Prooph\Link\Application\Service\ApplicationDbAware;
use Prooph\Link\ProcessManager\Model\MessageHandler\ProcessingTypes;
use Prooph\Link\ProcessManager\Projection\Tables;

/**
 * Class MessageHandlerFinder
 *
 * @package Prooph\Link\ProcessManager\Projection\MessageHandler
 * @author Alexander Miertsch <kontakt@codeliner.ws>
 */
final class MessageHandlerFinder implements ApplicationDbAware
{
    /**
     * @var Connection
     */
    private $connection;

    /**
     * @param string $id
     * @return array|null
     */
    public function find($id)
    {
        $handlerData = $this->connection->fetchAssoc('SELECT * FROM '.Tables::MESSAGE_HANDLER.' WHERE id = :id', ['id' => $id]);

        if (empty($handlerData)) {
            return null;
        }

        $this->fromDatabase($handlerData);

        return $handlerData;
    }

    /**
     * @return array of message handlers data
     */
    public function findAll()
    {
        $handlers = [];
        $stmt = $this->connection->prepare('SELECT * FROM ' . Tables::MESSAGE_HANDLER);
        $stmt->execute();

        foreach($stmt as $row) {
            $this->fromDatabase($row);
            array_push($handlers, $row);
        }

        return $handlers;
    }

    /**
     * Find all message handler with given processing_id
     * @param string $processingId
     * @return array of message handlers data
     */
    public function findByProcessingId($processingId)
    {
        $handlers = [];

        $builder = $this->connection->createQueryBuilder();

        $builder->select('*')->from(Tables::MESSAGE_HANDLER)
            ->where($builder->expr()->eq('processing_id', ':processing_id'))
            ->setParameter('processing_id', $processingId);

        foreach($builder->execute() as $row) {
            $this->fromDatabase($row);
            array_push($handlers, $row);
        }

        return $handlers;
    }

    /**
     * @param Connection $connection
     * @return mixed
     */
    public function setApplicationDb(Connection $connection)
    {
        $this->connection = $connection;
    }

    private function fromDatabase(&$handlerData)
    {
        if ($handlerData['processing_types'] !== ProcessingTypes::SUPPORT_ALL) {
            $handlerData['processing_types'] = explode(",", $handlerData['processing_types']);
        }

        $handlerData['processing_metadata'] = json_decode($handlerData['processing_metadata'], true);

        //Force object when metadata is empty,
        //otherwise the result json for the client would contain an array instead of an empty object
        if (empty($handlerData['processing_metadata'])) {
            $handlerData['processing_metadata'] = new \ArrayObject();
        }

        if (empty($handlerData['preferred_type'])) {
            $handlerData['preferred_type'] = null;
        }

        if (empty($handlerData['processing_id'])) {
            $handlerData['processing_id'] = null;
        }
    }
}