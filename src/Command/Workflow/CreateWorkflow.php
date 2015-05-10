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
use Prooph\Common\Messaging\Command;
use Prooph\Link\ProcessManager\Model\Workflow\WorkflowId;

/**
 * Command CreateWorkflow
 *
 * Command to create a new Workflow with a name and a unique WorkflowId
 *
 * @package Prooph\Link\ProcessManager\Model\Workflow
 * @author Alexander Miertsch <kontakt@codeliner.ws>
 */
final class CreateWorkflow extends Command
{
    /**
     * @param string $workflowName
     * @param string $workflowId
     * @return CreateWorkflow
     */
    public static function withName($workflowName, $workflowId)
    {
        Assertion::string($workflowName);
        Assertion::notEmpty($workflowName);
        Assertion::uuid($workflowId);

        return new self(
            __CLASS__,
            [
                'workflow_id' => $workflowId,
                'name' => $workflowName
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
     * @return string
     */
    public function workflowName()
    {
        return $this->payload['name'];
    }
}