<?php
/*
 * This file is part of prooph/link.
 * (c) 2014-2015 prooph software GmbH <contact@prooph.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 * 
 * Date: 4/18/15 - 6:32 PM
 */
namespace Prooph\Link\ProcessManager\Command\Workflow;
use Prooph\Link\Application\Service\TransactionCommand;
use Prooph\Link\Application\Service\TransactionId;
use Prooph\Link\Application\Service\TransactionIdGenerator;
use Prooph\Link\ProcessManager\Model\MessageHandler\MessageHandlerId;
use Prooph\Link\ProcessManager\Model\Task\TaskId;
use Prooph\Link\ProcessManager\Model\Workflow\WorkflowId;
use Prooph\ServiceBus\Message\MessageNameProvider;

/**
 * Command ScheduleNextTasksForWorkflow
 *
 * @package Prooph\Link\ProcessManager\Command\Workflow
 * @author Alexander Miertsch <kontakt@codeliner.ws>
 */
final class ScheduleNextTasksForWorkflow implements TransactionCommand, MessageNameProvider
{
    use TransactionIdGenerator;

    private $workflowId;

    private $previousTaskId;

    private $nextMessageHandlerId;

    /**
     * @param string|WorkflowId $workflowId
     * @param string|TaskId $previousTaskId
     * @param string|MessageHandlerId $nextMessageHandlerId
     */
    public function __construct($workflowId, $previousTaskId, $nextMessageHandlerId)
    {
        if (! $workflowId instanceof WorkflowId) {
            $workflowId = WorkflowId::fromString($workflowId);
        }

        if (! $previousTaskId instanceof TaskId) {
            $previousTaskId = TaskId::fromString($previousTaskId);
        }

        if (! $nextMessageHandlerId instanceof MessageHandlerId) {
            $nextMessageHandlerId = MessageHandlerId::fromString($nextMessageHandlerId);
        }

        $this->workflowId = $workflowId;
        $this->previousTaskId = $previousTaskId;
        $this->nextMessageHandlerId = $nextMessageHandlerId;
    }

    /**
     * @return WorkflowId
     */
    public function workflowId()
    {
        return $this->workflowId;
    }

    /**
     * @return TaskId
     */
    public function previousTaskId()
    {
        return $this->previousTaskId;
    }

    /**
     * @return MessageHandlerId
     */
    public function nextMessageHandlerId()
    {
        return $this->nextMessageHandlerId;
    }

    /**
     * @return string Name of the message
     */
    public function getMessageName()
    {
        return __CLASS__;
    }
}