<?php
/*
 * This file is part of prooph/link.
 * (c) 2014-2015 prooph software GmbH <contact@prooph.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 * 
 * Date: 4/12/15 - 7:48 PM
 */
namespace Prooph\Link\ProcessManager\Api;

use Prooph\Link\Application\Service\AbstractRestController;
use Prooph\Link\ProcessManager\Infrastructure\FlowchartStore;

/**
 * Class Flowchart
 *
 * @package Prooph\Link\ProcessManager\Api
 * @author Alexander Miertsch <kontakt@codeliner.ws>
 */
final class Flowchart extends AbstractRestController
{
    /**
     * @var FlowchartStore
     */
    private $flowchartStore;

    /**
     * @param FlowchartStore $flowchartStore
     */
    public function __construct(FlowchartStore $flowchartStore)
    {
        $this->flowchartStore = $flowchartStore;
    }

    public function create($data)
    {
        if (! array_key_exists('workflow_id', $data)) return $this->apiProblem(422, "No workflow_id given for the flowchart");
        if (! array_key_exists('config', $data)) return $this->apiProblem(422, "No config given for the flowchart");

        $this->flowchartStore->addFlowchartConfig($data['workflow_id'], $data['config']);

        return $this->location(
            $this->url()->fromRoute('prooph.link/process_config/api/flowchart', ['id' => $data['workflow_id']])
        );
    }

    public function update($id, $data)
    {
        if (! array_key_exists('workflow_id', $data)) return $this->apiProblem(422, "No workflow_id given for the flowchart");
        if (! array_key_exists('config', $data)) return $this->apiProblem(422, "No config given for the flowchart");

        $this->flowchartStore->updateFlowchartConfig($data['workflow_id'], $data['config']);

        return $this->accepted();
    }

    public function get($id)
    {
        $flowchartConfig = $this->flowchartStore->getFlowchartConfig($id);

        if (is_null($flowchartConfig)) {
            return $this->apiProblem(404, 'Flowchart not found');
        }

        return ['flowchart' => $flowchartConfig];
    }
} 