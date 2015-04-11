<?php
/*
 * This file is part of prooph/link.
 * (c) 2014-2015 prooph software GmbH <contact@prooph.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 * 
 * Date: 4/11/15 - 11:52 PM
 */
namespace Prooph\Link\ProcessManager\Api;

use Prooph\Link\Application\Service\AbstractRestController;
use Prooph\Link\Application\Service\ActionController;
use Prooph\Link\ProcessManager\Command\MessageHandler\InstallMessageHandler;
use Prooph\Link\ProcessManager\Model\MessageHandler\MessageHandlerId;
use Prooph\Link\ProcessManager\Model\MessageHandler\ProcessingTypes;
use Prooph\Link\ProcessManager\Model\ProcessingMetadata;
use Prooph\ServiceBus\CommandBus;

final class MessageHandler extends AbstractRestController implements ActionController
{
    /**
     * @var CommandBus
     */
    private $commandBus;

    public function create($data)
    {
        if (! array_key_exists('message_handler', $data)) return $this->apiProblem(422, "Missing root key -message_handler-");

        $data = $data['message_handler'];

        if (! array_key_exists('name', $data)) return $this->apiProblem(422, "No name given for the message handler");
        if (! array_key_exists('node_name', $data)) return $this->apiProblem(422, "No node_name given for the message handler");
        if (! array_key_exists('type', $data)) return $this->apiProblem(422, "No type given for the message handler");
        if (! array_key_exists('data_direction', $data)) return $this->apiProblem(422, "No data_direction given for the message handler");

        if (!isset($data['processing_types'])) {
            $data['processing_types'] = ProcessingTypes::SUPPORT_ALL;
        }

        if (!isset($data['processing_metadata'])) {
            $data['processing_metadata'] = ProcessingMetadata::noData();
        }

        if (!isset($data['preferred_type'])) {
            $data['preferred_type'] = null;
        }

        if (!isset($data['processing_id'])) {
            $data['processing_id'] = null;
        }

        $messageHandlerId = MessageHandlerId::generate();

        $this->commandBus->dispatch(new InstallMessageHandler(
            $messageHandlerId,
            $data['name'],
            $data['node_name'],
            $data['type'],
            $data['data_direction'],
            $data['processing_types'],
            $data['processing_metadata'],
            $data['preferred_type'],
            $data['processing_id']
        ));

        return $this->location(
            $this->url()->fromRoute('prooph.link/process_config/api/message-handler', ['id' => $messageHandlerId->toString()])
        );
    }

    /**
     * @param CommandBus $commandBus
     * @return void
     */
    public function setCommandBus(CommandBus $commandBus)
    {
        $this->commandBus = $commandBus;
    }
}