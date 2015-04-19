<?php
/*
 * This file is part of prooph/link.
 * (c) 2014-2015 prooph software GmbH <contact@prooph.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 * 
 * Date: 4/18/15 - 10:43 PM
 */
namespace Prooph\Link\ProcessManager\Command\Workflow;

use Assert\Assertion;
use Prooph\Link\Application\Service\TransactionCommand;
use Prooph\Link\Application\Service\TransactionIdGenerator;
use Prooph\Link\ProcessManager\Model\Workflow\WorkflowId;
use Prooph\ServiceBus\Message\MessageNameProvider;

/**
 * Command PublishWorkflow
 *
 * @package Prooph\Link\ProcessManager\Command\Workflow
 * @author Alexander Miertsch <kontakt@codeliner.ws>
 */
final class PublishWorkflow implements TransactionCommand, MessageNameProvider
{
    use TransactionIdGenerator;

    /**
     * @var WorkflowId
     */
    private $workflowId;

    /**
     * @var int
     */
    private $releaseNumber;

    /**
     * @param string|WorkflowId $workflowId
     * @param int $releaseNumber
     */
    public function __construct($workflowId, $releaseNumber)
    {
        if (! $workflowId instanceof WorkflowId) {
            $workflowId = WorkflowId::fromString($workflowId);
        }

        Assertion::integer($releaseNumber);

        $this->workflowId = $workflowId;

        $this->releaseNumber = $releaseNumber;
    }

    /**
     * @return WorkflowId
     */
    public function workflowId()
    {
        return $this->workflowId;
    }

    /**
     * @return int
     */
    public function releaseNumber()
    {
        return $this->releaseNumber;
    }

    /**
     * @return string Name of the message
     */
    public function getMessageName()
    {
        return __CLASS__;
    }
}