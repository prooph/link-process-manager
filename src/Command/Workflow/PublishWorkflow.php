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
use Prooph\Common\Messaging\Command;
use Prooph\Link\ProcessManager\Model\Workflow\WorkflowId;

/**
 * Command PublishWorkflow
 *
 * @package Prooph\Link\ProcessManager\Command\Workflow
 * @author Alexander Miertsch <kontakt@codeliner.ws>
 */
final class PublishWorkflow extends Command
{
    public static function withReleaseNumber($releaseNumber, $workflowId)
    {
        Assertion::integer($releaseNumber);
        Assertion::uuid($workflowId);

        return new self(
            __CLASS__,
            [
                'workflow_id' => $workflowId,
                'release_number' => $releaseNumber
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
     * @return int
     */
    public function releaseNumber()
    {
        return $this->payload['release_number'];
    }
}