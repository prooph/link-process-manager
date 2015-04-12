<?php
/*
 * This file is part of prooph/link.
 * (c) 2014-2015 prooph software GmbH <contact@prooph.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 * 
 * Date: 4/12/15 - 5:05 PM
 */
namespace Prooph\Link\ProcessManager\Model\Workflow;

use Prooph\Link\ProcessManager\Command\Workflow\ChangeWorkflowName;
use Prooph\Link\ProcessManager\Model\Workflow\Exception\WorkflowNotFound;

/**
 * Class ChangeWorkflowNameHandler
 *
 * @package Prooph\Link\ProcessManager\Model\Workflow
 * @author Alexander Miertsch <kontakt@codeliner.ws>
 */
final class ChangeWorkflowNameHandler 
{
    /**
     * @var WorkflowCollection
     */
    private $workflowCollection;

    /**
     * @param WorkflowCollection $workflowCollection
     */
    public function __construct(WorkflowCollection $workflowCollection)
    {
        $this->workflowCollection = $workflowCollection;
    }

    /**
     * @param ChangeWorkflowName $command
     * @throws Exception\WorkflowNotFound
     */
    public function handle(ChangeWorkflowName $command)
    {
        $workflow = $this->workflowCollection->get($command->workflowId());

        if (is_null($workflow)) {
            throw WorkflowNotFound::withId($command->workflowId());
        }

        $workflow->changeName($command->name());
    }
} 