<?php
/*
 * This file is part of prooph/link.
 * (c) 2014-2015 prooph software GmbH <contact@prooph.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 * 
 * Date: 4/11/15 - 11:12 PM
 */
namespace Prooph\Link\ProcessManager\Infrastructure;

use Prooph\EventSourcing\EventStoreIntegration\AggregateTranslator;
use Prooph\EventStore\Aggregate\AggregateRepository;
use Prooph\EventStore\Aggregate\AggregateType;
use Prooph\EventStore\EventStore;
use Prooph\EventStore\Stream\SingleStreamStrategy;
use Prooph\Link\ProcessManager\Model\MessageHandler\MessageHandlerCollection;
use Prooph\Link\ProcessManager\Model\MessageHandler;
use Prooph\Link\ProcessManager\Model\MessageHandler\MessageHandlerId;

/**
 * Class PESMessageHandlerCollection
 *
 * @package Prooph\Link\ProcessManager\Infrastructure
 * @author Alexander Miertsch <kontakt@codeliner.ws>
 */
final class PESMessageHandlerCollection extends AggregateRepository implements MessageHandlerCollection
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
            AggregateType::fromAggregateRootClass('Prooph\Link\ProcessManager\Model\MessageHandler')
        );
    }

    /**
     * @param MessageHandler $messageHandler
     * @return void
     */
    public function add(MessageHandler $messageHandler)
    {
        $this->addAggregateRoot($messageHandler);
    }

    /**
     * @param MessageHandlerId $handlerId
     * @return MessageHandler
     */
    public function get(MessageHandlerId $handlerId)
    {
        $this->getAggregateRoot($handlerId->toString());
    }
}