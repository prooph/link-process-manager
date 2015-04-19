<?php
/*
 * This file is part of prooph/link.
 * (c) 2014-2015 prooph software GmbH <contact@prooph.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 * 
 * Date: 4/11/15 - 11:29 PM
 */
namespace Prooph\Link\ProcessManager\Projection\MessageHandler;

use Doctrine\DBAL\Connection;
use Prooph\Link\Application\Service\ApplicationDbAware;
use Prooph\Link\ProcessManager\Model\MessageHandler\MessageHandlerWasInstalled;
use Prooph\Link\ProcessManager\Model\MessageHandler\ProcessingTypes;
use Prooph\Link\ProcessManager\Projection\Tables;

final class MessageHandlerProjector implements ApplicationDbAware
{
    /**
     * @var Connection
     */
    private $connection;

    /**
     * @param MessageHandlerWasInstalled $event
     */
    public function onMessageHandlerWasInstalled(MessageHandlerWasInstalled $event)
    {
        $processingTypes = $event->supportedProcessingTypes()->toArray();

        $this->connection->insert(Tables::MESSAGE_HANDLER, [
            'id' => $event->messageHandlerId()->toString(),
            'name' => $event->messageHandlerName(),
            'node_name' => $event->processingNodeName()->toString(),
            'type' => $event->handlerType()->toString(),
            'data_direction' => $event->dataDirection()->toString(),
            'processing_types' => $processingTypes['support_all']? ProcessingTypes::SUPPORT_ALL : implode(',', $processingTypes['processing_types']),
            'processing_metadata' => json_encode($event->processingMetadata()->toArray()),
            'metadata_riot_tag' => $event->metadataRiotTag(),
            'icon' => $event->icon(),
            'icon_type' => $event->iconType(),
            'preferred_type' => (! is_null($event->preferredProcessingType()))? $event->preferredProcessingType()->of() : null,
            'processing_id' => (! is_null($event->processingId()))? $event->processingId()->toString() : null,
            'additional_data' => json_encode($event->additionalData()),
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