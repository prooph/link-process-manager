<?php
/*
 * This file is part of prooph/link.
 * (c) 2014-2015 prooph software GmbH <contact@prooph.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 * 
 * Date: 4/12/15 - 5:00 PM
 */
namespace Prooph\Link\ProcessManager\Command\Workflow;

use Assert\Assertion;
use Prooph\Link\Application\Service\TransactionCommand;
use Prooph\Link\ProcessManager\Model\Workflow\WorkflowId;
use Prooph\ServiceBus\Message\MessageNameProvider;

/**
 * Command ChangeWorkflowName
 *
 * @package Prooph\Link\ProcessManager\Command\Workflow
 * @author Alexander Miertsch <kontakt@codeliner.ws>
 */
final class ChangeWorkflowName implements TransactionCommand, MessageNameProvider
{
    /**
     * @var WorkflowId
     */
    private $workflowId;

    /**
     * @var string
     */
    private $name;

    /**
     * @param string|WorkflowId $workflowId
     * @param string $name
     */
    public function __construct($workflowId, $name)
    {
        if (! $workflowId instanceof WorkflowId) {
            $workflowId = WorkflowId::fromString($workflowId);
        }

        $this->workflowId = $workflowId;

        Assertion::string($name);
        Assertion::notEmpty($name);

        $this->name = $name;
    }

    /**
     * @return WorkflowId
     */
    public function workflowId()
    {
        return $this->workflowId;
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