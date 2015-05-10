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
use Assert\Assertion;
use Prooph\Common\Messaging\Command;
use Prooph\Link\ProcessManager\Model\MessageHandler\MessageHandlerId;
use Prooph\Link\ProcessManager\Model\Task\TaskId;
use Prooph\Link\ProcessManager\Model\Workflow\WorkflowId;

/**
 * Command ScheduleNextTasksForWorkflow
 *
 * @package Prooph\Link\ProcessManager\Command\Workflow
 * @author Alexander Miertsch <kontakt@codeliner.ws>
 */
final class ScheduleNextTasksForWorkflow extends Command
{
    public static function withData($workflowId, $previousTaskId, $nextMessageHandlerId)
    {
        Assertion::uuid($workflowId);
        Assertion::uuid($previousTaskId);
        Assertion::uuid($nextMessageHandlerId);

        return new self(
            __CLASS__,
            [
                'workflow_id' => $workflowId,
                'previous_task_id' => $previousTaskId,
                'next_message_handler_id' => $nextMessageHandlerId
            ]
        );
    }

    /**
     * @return WorkflowId
     */
    public function workflowId()
    {
        return WorkflowId::fromString($this->payload['workflow_id']);
    }

    /**
     * @return TaskId
     */
    public function previousTaskId()
    {
        return TaskId::fromString($this->payload['previous_task_id']);
    }

    /**
     * @return MessageHandlerId
     */
    public function nextMessageHandlerId()
    {
        return MessageHandlerId::fromString($this->payload['next_message_handler_id']);
    }
}