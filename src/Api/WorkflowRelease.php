<?php
/*
 * This file is part of prooph/link.
 * (c) 2014-2015 prooph software GmbH <contact@prooph.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 * 
 * Date: 4/18/15 - 9:55 PM
 */
namespace Prooph\Link\ProcessManager\Api;

use Prooph\Link\Application\Service\AbstractRestController;
use Prooph\Link\Application\Service\ActionController;
use Prooph\Link\ProcessManager\Command\Workflow\PublishWorkflow;
use Prooph\Link\ProcessManager\Projection\Workflow\WorkflowFinder;
use Prooph\ServiceBus\CommandBus;

/**
 * Resource WorkflowRelease
 *
 * A workflow release represents a version of a workflow published to the ProcessingConfig so that the workflow
 * can be processed.
 *
 * @package Prooph\Link\ProcessManager\Api
 * @author Alexander Miertsch <kontakt@codeliner.ws>
 */
final class WorkflowRelease extends AbstractRestController implements ActionController
{
    /**
     * @var CommandBus
     */
    private $commandBus;

    /**
     * @var WorkflowFinder
     */
    private $workflowFinder;

    public function create($data)
    {
        if (! array_key_exists('workflow_id', $data)) return $this->apiProblem(422, "No workflow_id given for the release");

        $workflowData = $this->workflowFinder->find($data['workflow_id']);

        if (! $workflowData) {
            return $this->apiProblem(404, "Workflow can not be found");
        }

        $newRelease = (int)$workflowData['current_release'];
        $newRelease++;

        $this->commandBus->dispatch(PublishWorkflow::withReleaseNumber($newRelease, $data['workflow_id']));

        return $this->location(
            $this->url()->fromRoute('prooph.link/process_config/api/workflow_release', ['id' => $newRelease])
        );
    }

    public function get($id)
    {
        //@TODO: Implement get release, should return the flowchart config id and the version of the workflow assigned to the release
        return $this->apiProblem(405, "Get release is currently not supported");
    }

    /**
     * @param CommandBus $commandBus
     * @return void
     */
    public function setCommandBus(CommandBus $commandBus)
    {
        $this->commandBus = $commandBus;
    }

    /**
     * @param WorkflowFinder $workflowFinder
     */
    public function setWorkflowFinder(WorkflowFinder $workflowFinder)
    {
        $this->workflowFinder = $workflowFinder;
    }
}