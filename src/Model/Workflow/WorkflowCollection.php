<?php
/*
 * This file is part of prooph/link.
 * (c) 2014-2015 prooph software GmbH <contact@prooph.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 * 
 * Date: 4/4/15 - 12:09 AM
 */
namespace Prooph\Link\ProcessManager\Model\Workflow;

use Prooph\Link\ProcessManager\Model\Workflow;

/**
 * WorkflowCollection
 *
 * @package Prooph\Link\ProcessManager\Model\Workflow
 * @author Alexander Miertsch <kontakt@codeliner.ws>
 */
interface WorkflowCollection
{
    /**
     * @param WorkflowId $workflowId
     * @return Workflow
     */
    public function get(WorkflowId $workflowId);

    /**
     * @param Workflow $workflow
     * @return void
     */
    public function add(Workflow $workflow);
} 