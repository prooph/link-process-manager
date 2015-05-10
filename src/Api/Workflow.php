<?php
/*
 * This file is part of prooph/link.
 * (c) 2014-2015 prooph software GmbH <contact@prooph.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 * 
 * Date: 4/3/15 - 10:01 PM
 */
namespace Prooph\Link\ProcessManager\Api;

use Prooph\Link\Application\Service\AbstractRestController;
use Prooph\Link\Application\Service\ActionController;
use Prooph\Link\ProcessManager\Command\Workflow\ChangeWorkflowName;
use Prooph\Link\ProcessManager\Command\Workflow\CreateWorkflow;
use Prooph\Link\ProcessManager\Model\Workflow\Exception\WorkflowNotFound;
use Prooph\Link\ProcessManager\Model\Workflow\WorkflowId;
use Prooph\Link\ProcessManager\Projection\Workflow\WorkflowFinder;
use Prooph\ServiceBus\CommandBus;
use Prooph\ServiceBus\Exception\CommandDispatchException;

/**
 * Workflow Resource
 *
 * @package Prooph\Link\ProcessManager\Api
 * @author Alexander Miertsch <kontakt@codeliner.ws>
 */
final class Workflow extends AbstractRestController implements ActionController
{
    /**
     * @var CommandBus
     */
    private $commandBus;

    /**
     * @var WorkflowFinder
     */
    private $workflowFinder;

    /**
     * @param mixed $data
     * @return mixed|void
     */
    public function create($data)
    {
        if (! array_key_exists('name', $data)) return $this->apiProblem(422, "No name given for the workflow");

        $workflowId = WorkflowId::generate();

        try {
            $command = CreateWorkflow::withName($data['name'], $workflowId);
        } catch (\Exception $e) {
            return $this->apiProblem(422, $e->getMessage());
        }

        $this->commandBus->dispatch($command);

        return $this->location(
            $this->url()->fromRoute('prooph.link/process_config/api/workflow', ['id' => $workflowId->toString()])
        );
    }

    public function getList()
    {
        $workflows = $this->workflowFinder->findAll();

        return [
            'workflow_collection' => $workflows
        ];
    }

    public function get($id)
    {
        $workflow = $this->workflowFinder->find($id);

        if (is_null($workflow)) {
            return $this->apiProblem(404, "Workflow not found");
        }

        return ['workflow' => $workflow];
    }

    public function update($id, $data)
    {
        if (! array_key_exists('name', $data)) return $this->apiProblem(422, "No name given for the workflow");

        try {
            $this->commandBus->dispatch(ChangeWorkflowName::to($data['name'], $id));
        } catch (CommandDispatchException $ex) {
            if ($ex->getFailedCommandDispatch()->getException() instanceof WorkflowNotFound) {
                return $this->apiProblem(404, "Workflow not found");
            } else {
                throw $ex->getFailedCommandDispatch()->getException();
            }
        }

        return $this->accepted();
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