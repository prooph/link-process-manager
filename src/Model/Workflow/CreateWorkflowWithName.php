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
namespace Prooph\Link\ProcessManager\Model\Workflow;

use Assert\Assertion;
use Prooph\Link\Application\Service\TransactionCommand;
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
     * @param WorkflowId $workflowId
     * @param string $workflowName
     */
    public function __construct(WorkflowId $workflowId, $workflowName)
    {
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