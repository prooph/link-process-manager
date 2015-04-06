<?php
/*
 * This file is part of prooph/link.
 * (c) 2014-2015 prooph software GmbH <contact@prooph.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 * 
 * Date: 4/5/15 - 1:22 AM
 */
namespace Prooph\Link\ProcessManager\Model;

use Assert\Assertion;
use Prooph\EventSourcing\AggregateRoot;
use Prooph\Link\ProcessManager\Model\MessageHandler\DataDirection;
use Prooph\Link\ProcessManager\Model\MessageHandler\HandlerType;
use Prooph\Link\ProcessManager\Model\MessageHandler\MessageHandlerId;
use Prooph\Link\ProcessManager\Model\MessageHandler\MessageHandlerWasCreated;
use Prooph\Link\ProcessManager\Model\MessageHandler\ProcessingId;
use Prooph\Link\ProcessManager\Model\Task\TaskId;
use Prooph\Processing\Processor\NodeName;
use Prooph\Processing\Type\Prototype;

/**
 * Class MessageHandler
 *
 * The aggregate represents a processing workflow message handler in the process manager model.
 * It is not the real message handler implementation but contains information and provides methods to deal with a
 * message handler within the process manager domain.
 *
 * @package Prooph\Link\ProcessManager\Model
 * @author Alexander Miertsch <kontakt@codeliner.ws>
 */
final class MessageHandler extends AggregateRoot
{
    /**
     * Unique identifier of the message handler across the process manager domain
     *
     * @var MessageHandlerId
     */
    private $messageHandlerId;

    /**
     * Name of the message handler, defined by the client
     *
     * @var string
     */
    private $name;

    /**
     * Connected process task
     *
     * @var TaskId
     */
    private $taskId;

    /**
     * Defines either the handler acts as a source (collects data) or as a target (processes data)
     *
     * @var DataDirection
     */
    private $dataDirection;

    /**
     * Defines the type of the message handler
     *
     * @var HandlerType
     */
    private $handlerType;

    /**
     * Defines the processing node on which the handler is installed
     *
     * @var NodeName
     */
    private $processingNodeName;

    /**
     * Defines the identifier of the message handler implementation in the processing system
     *
     * @var ProcessingId
     */
    private $processingId;

    /**
     * @var Prototype[]
     */
    private $allowedProcessingTypes;

    /**
     * @var Prototype
     */
    private $preferredProcessingType;

    /**
     * Contains the processing metadata for the processing message handler implementation
     *
     * @var array
     */
    private $processingMetadata;

    /**
     * @param MessageHandlerId $id
     * @param string $name
     * @return MessageHandler
     */
    public static function createWithName(MessageHandlerId $id, $name)
    {
        Assertion::string($name);
        Assertion::notEmpty($name);

        $instance = new self();

        $instance->recordThat(MessageHandlerWasCreated::occur($id->toString(), ['name' => $name]));

        return $instance;
    }

    /**
     * @return MessageHandlerId
     */
    public function messageHandlerId()
    {
        return $this->messageHandlerId;
    }

    /**
     * @return string
     */
    public function name()
    {
        return $this->name;
    }

    /**
     * @param MessageHandlerWasCreated $event
     */
    protected function whenMessageHandlerWasCreated(MessageHandlerWasCreated $event)
    {
        $this->messageHandlerId = $event->messageHandlerId();
        $this->name = $event->messageHandlerName();
    }

    /**
     * @return string representation of the unique identifier of the aggregate root
     */
    protected function aggregateId()
    {
        return $this->messageHandlerId->toString();
    }
}