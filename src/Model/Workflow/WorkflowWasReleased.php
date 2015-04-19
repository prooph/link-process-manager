<?php
/*
 * This file is part of prooph/link.
 * (c) 2014-2015 prooph software GmbH <contact@prooph.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 * 
 * Date: 4/19/15 - 12:34 AM
 */
namespace Prooph\Link\ProcessManager\Model\Workflow;

use Prooph\EventSourcing\AggregateChanged;

/**
 * Event WorkflowWasReleased
 *
 * @package Prooph\Link\ProcessManager\Model\Workflow
 * @author Alexander Miertsch <kontakt@codeliner.ws>
 */
final class WorkflowWasReleased extends AggregateChanged
{
    /**
     * @param WorkflowId $workflowId
     * @param int $releasedVersion
     * @param int $releaseNumber
     * @return WorkflowWasReleased
     */
    public static function withVersion(WorkflowId $workflowId, $releasedVersion, $releaseNumber)
    {
        return self::occur($workflowId->toString(), ['released_version' => $releasedVersion, 'release_number' => $releaseNumber]);
    }

    /**
     * @return WorkflowId
     */
    public function workflowId()
    {
        return WorkflowId::fromString($this->aggregateId());
    }

    /**
     * @return int
     */
    public function releasedVersion()
    {
        return $this->toPayloadReader()->integerValue('released_version');
    }

    /**
     * @return int
     */
    public function releaseNumber()
    {
        return $this->toPayloadReader()->integerValue('release_number');
    }
}