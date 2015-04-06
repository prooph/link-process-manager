<?php
/*
 * This file is part of prooph/link.
 * (c) 2014-2015 prooph software GmbH <contact@prooph.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 * 
 * Date: 4/5/15 - 9:17 PM
 */
namespace Prooph\Link\ProcessManager\Command\MessageHandler;

use Assert\Assertion;
use Prooph\Link\Application\Service\TransactionCommand;
use Prooph\Link\ProcessManager\Model\MessageHandler\MessageHandlerId;
use Prooph\ServiceBus\Message\MessageNameProvider;

/**
 * Class CreateMessageHandlerWithName
 *
 * @package Prooph\Link\ProcessManager\Command\MessageHandler
 * @author Alexander Miertsch <kontakt@codeliner.ws>
 */
final class CreateMessageHandlerWithName implements TransactionCommand, MessageNameProvider
{
    /**
     * @var MessageHandlerId
     */
    private $messageHandlerId;

    /**
     * @var string
     */
    private $name;
    /**
     * @param MessageHandlerId $id
     * @param string $name
     */
    public function __construct(MessageHandlerId $id, $name)
    {
        Assertion::string($name);
        Assertion::notEmpty($name);

        $this->messageHandlerId = $id;
        $this->name = $name;
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
     * @return string Name of the message
     */
    public function getMessageName()
    {
        return __CLASS__;
    }
}