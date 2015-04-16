<?php
/*
 * This file is part of prooph/link.
 * (c) 2014-2015 prooph software GmbH <contact@prooph.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 * 
 * Date: 4/4/15 - 4:39 PM
 */
namespace Prooph\Link\ProcessManager\Projection\Workflow;

use Doctrine\DBAL\Connection;
use Prooph\Link\Application\Service\ApplicationDbAware;
use Prooph\Link\ProcessManager\Model\Workflow\Message;
use Prooph\Link\ProcessManager\Model\Workflow\MessageType;
use Prooph\Link\ProcessManager\Model\Workflow\StartMessageWasAssignedToWorkflow;
use Prooph\Link\ProcessManager\Model\Workflow\WorkflowNameWasChanged;
use Prooph\Link\ProcessManager\Model\Workflow\WorkflowWasCreated;
use Prooph\Link\ProcessManager\Projection\Tables;
use Prooph\Processing\Message\MessageNameUtils;

/**
 * Class WorkflowProjector
 *
 * @package Prooph\Link\ProcessManager\Projection\Workflow
 * @author Alexander Miertsch <kontakt@codeliner.ws>
 */
final class WorkflowProjector implements ApplicationDbAware
{
    /**
     * @var Connection
     */
    private $connection;

    /**
     * @param WorkflowWasCreated $event
     */
    public function onWorkflowWasCreated(WorkflowWasCreated $event)
    {
        $this->connection->insert(Tables::WORKFLOW, [
            'id' => $event->workflowId()->toString(),
            'name' => $event->workflowName(),
            'node_name' => $event->processingNodeName()->toString()
        ]);
    }

    /**
     * @param WorkflowNameWasChanged $event
     */
    public function onWorkflowNameWasChanged(WorkflowNameWasChanged $event)
    {
        $this->connection->update(Tables::WORKFLOW, ['name' => $event->newName()], ['id' => $event->workflowId()->toString()]);
    }

    /**
     * @param StartMessageWasAssignedToWorkflow $event
     */
    public function onStartMessageWasAssignedToWorkflow(StartMessageWasAssignedToWorkflow $event)
    {
        $this->connection->update(
            Tables::WORKFLOW,
            ['start_message' => $this->determineMessageName($event->startMessage())],
            ['id' => $event->workflowId()->toString()]
        );
    }

    /**
     * @param Connection $connection
     * @return mixed
     */
    public function setApplicationDb(Connection $connection)
    {
        $this->connection = $connection;
    }

    /**
     * @param Message $message
     * @return string
     */
    private function determineMessageName(Message $message)
    {
        switch($message->messageType()->toString()) {
            case MessageType::TYPE_DATA_PROCESSED:
                return MessageNameUtils::getDataProcessedEventName($message->processingType()->of());
            case MessageType::TYPE_PROCESS_DATA:
                return MessageNameUtils::getProcessDataCommandName($message->processingType()->of());
            case MessageType::TYPE_DATA_COLLECTED:
                return MessageNameUtils::getDataCollectedEventName($message->processingType()->of());
            case MessageType::TYPE_COLLECT_DATA:
                return MessageNameUtils::getCollectDataCommandName($message->processingType()->of());
        }
    }
}