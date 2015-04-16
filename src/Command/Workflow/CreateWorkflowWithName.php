<?php
/*
 * This file is part of prooph/link.
 * (c) 2014-2015 prooph software GmbH <contact@prooph.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 * 
 * Date: 4/4/15 - 12:51 AM
 */
namespace Prooph\Link\ProcessManager\Command\Workflow;

use Assert\Assertion;
use Prooph\Link\Application\Service\TransactionCommand;
use Prooph\Link\Application\Service\TransactionIdGenerator;
use Prooph\Link\ProcessManager\Model\Workflow\WorkflowId;
use Prooph\ServiceBus\Message\MessageNameProvider;

/**
 * Command CreateWorkflowWithName
 *
 * Command to create a new Workflow with a name and a unique WorkflowId
 *
 * @package Prooph\Link\ProcessManager\Model\Workflow
 * @author Alexander Miertsch <kontakt@codeliner.ws>
 */
final class CreateWorkflowWithName implements TransactionCommand, MessageNameProvider
{
    use TransactionIdGenerator;

    /**
     * @var WorkflowId
     */
    private $wokflowId;

    /**
     * @var string
     */
    private $workflowName;

    /**
     * @return string Name of the message
     */
    public function getMessageName()
    {
        return __CLASS__;
    }

    /**
     * @param WorkflowId|string $workflowId
     * @param string $workflowName
     */
    public function __construct($workflowId, $workflowName)
    {
        if (! $workflowId instanceof WorkflowId) {
            $workflowId = WorkflowId::fromString($workflowId);
        }

        Assertion::string($workflowName);
        Assertion::notEmpty($workflowName);

        $this->wokflowId = $workflowId;
        $this->workflowName = $workflowName;
    }

    /**
     * @return WorkflowId
     */
    public function workflowId()
    {
        return $this->wokflowId;
    }

    /**
     * @return string
     */
    public function workflowName()
    {
        return $this->workflowName;
    }
}