<?php
/*
 * This file is part of prooph/link.
 * (c) 2014-2015 prooph software GmbH <contact@prooph.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 * 
 * Date: 4/19/15 - 6:43 PM
 */
namespace Prooph\Link\ProcessManager\Model\Workflow;
use Prooph\Link\ProcessManager\Command\Workflow\PublishWorkflow;
use Prooph\Link\ProcessManager\Model\Workflow\Exception\WorkflowNotFound;

/**
 * CommandHandler PublishWorkflowHandler
 *
 * @package Prooph\Link\ProcessManager\Model\Workflow
 * @author Alexander Miertsch <kontakt@codeliner.ws>
 */
final class PublishWorkflowHandler 
{
    /**
     * @var WorkflowCollection
     */
    private $workflowCollection;

    /**
     * @var WorkflowPublisher
     */
    private $workflowPublisher;

    /**
     * @param WorkflowCollection $workflowCollection
     * @param WorkflowPublisher $workflowPublisher
     */
    public function __construct(WorkflowCollection $workflowCollection, WorkflowPublisher $workflowPublisher)
    {
        $this->workflowCollection = $workflowCollection;
        $this->workflowPublisher  = $workflowPublisher;
    }

    /**
     * @param PublishWorkflow $command
     * @throws Exception\WorkflowNotFound
     */
    public function handle(PublishWorkflow $command)
    {
        $workflow = $this->workflowCollection->get($command->workflowId());

        if (is_null($workflow)) {
            throw WorkflowNotFound::withId($command->workflowId());
        }

        $workflow->releaseCurrentVersion($command->releaseNumber(), $this->workflowPublisher);
    }
} 