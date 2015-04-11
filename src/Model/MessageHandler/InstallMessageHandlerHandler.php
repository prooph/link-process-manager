<?php
/*
 * This file is part of prooph/link.
 * (c) 2014-2015 prooph software GmbH <contact@prooph.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 * 
 * Date: 4/11/15 - 9:25 PM
 */
namespace Prooph\Link\ProcessManager\Model\MessageHandler;

use Prooph\Link\ProcessManager\Command\MessageHandler\InstallMessageHandler;
use Prooph\Link\ProcessManager\Model\ProcessingNodeList;

/**
 * CommandHandler InstallMessageHandlerHandler
 *
 * @package Prooph\Link\ProcessManager\Model\MessageHandler
 * @author Alexander Miertsch <kontakt@codeliner.ws>
 */
final class InstallMessageHandlerHandler
{
    /**
     * @var ProcessingNodeList
     */
    private $processingNodeList;

    /**
     * @var MessageHandlerCollection
     */
    private $messageHandlerCollection;

    /**
     * @param ProcessingNodeList $processingNodeList
     * @param MessageHandlerCollection $collection
     */
    public function __construct(ProcessingNodeList $processingNodeList, MessageHandlerCollection $collection)
    {
        $this->processingNodeList = $processingNodeList;
        $this->messageHandlerCollection = $collection;
    }

    /**
     * @param InstallMessageHandler $command
     */
    public function handle(InstallMessageHandler $command)
    {
        $node = $this->processingNodeList->getNode($command->nodeName());

        $handler = $node->installMessageHandler(
            $command->messageHandlerId(),
            $command->messageHandlerName(),
            $command->handlerType(),
            $command->dataDirection(),
            $command->supportedProcessingTypes(),
            $command->processingMetadata(),
            $command->preferredProcessingType(),
            $command->handlerProcessingId()
        );

        $this->messageHandlerCollection->add($handler);
    }
} 