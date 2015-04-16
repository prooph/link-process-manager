<?php
/*
 * This file is part of prooph/link.
 * (c) 2014-2015 prooph software GmbH <contact@prooph.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 * 
 * Date: 4/12/15 - 5:22 PM
 */
namespace Prooph\Link\ProcessManager\Model\Workflow;

use Prooph\EventSourcing\AggregateChanged;
use Prooph\Link\Application\Service\TransactionEvent;
use Prooph\Link\Application\Service\TransactionIdAware;

/**
 * Event WorkflowNameWasChanged
 *
 * @package Prooph\Link\ProcessManager\Model\Workflow
 * @author Alexander Miertsch <kontakt@codeliner.ws>
 */
final class WorkflowNameWasChanged extends AggregateChanged implements TransactionEvent
{
    use TransactionIdAware;

    private $workflowId;

    /**
     * @param WorkflowId $workflowId
     * @param string $oldName
     * @param string $newName
     * @return WorkflowNameWasChanged
     */
    public static function record(WorkflowId $workflowId, $oldName, $newName)
    {
        $event = self::occur($workflowId->toString(), ['old_name' => $oldName, 'new_name' => $newName]);

        $event->workflowId = $workflowId;

        return $event;
    }

    /**
     * @return WorkflowId
     */
    public function workflowId()
    {
        if (is_null($this->workflowId)) {
            $this->workflowId = WorkflowId::fromString($this->aggregateId());
        }
        return $this->workflowId;
    }

    /**
     * @return string
     */
    public function oldName()
    {
        return $this->payload['old_name'];
    }

    /**
     * @return string
     */
    public function newName()
    {
        return $this->payload['new_name'];
    }
} 