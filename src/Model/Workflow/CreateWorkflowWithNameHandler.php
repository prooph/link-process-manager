<?php
/*
 * This file is part of prooph/link.
 * (c) 2014-2015 prooph software GmbH <contact@prooph.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 * 
 * Date: 4/4/15 - 1:05 AM
 */
namespace Prooph\Link\ProcessManager\Model\Workflow;

use Prooph\Link\ProcessManager\Command\Workflow\CreateWorkflowWithName;
use Prooph\Link\ProcessManager\Model\Workflow;

/**
 * CreateWorkflowWithNameHandler
 *
 * @package Prooph\Link\ProcessManager\Model\Workflow
 * @author Alexander Miertsch <kontakt@codeliner.ws>
 */
final class CreateWorkflowWithNameHandler 
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
     * @param CreateWorkflowWithName $command
     */
    public function handle(CreateWorkflowWithName $command)
    {
        $workflow = Workflow::createWithName($command->workflowId(), $command->workflowName());

        $this->workflowCollection->add($workflow);
    }
} 