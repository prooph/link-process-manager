<?php
/*
 * This file is part of prooph/link.
 * (c) 2014-2015 prooph software GmbH <contact@prooph.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 * 
 * Date: 4/19/15 - 12:26 AM
 */

namespace Prooph\Link\ProcessManager\Model\Workflow;

use Prooph\Link\ProcessManager\Model\Workflow;

/**
 * Interface WorkflowPublisher
 *
 * The WorkflowPublisher is responsible for parsing a workflow definition
 * and adding or updating it in the processing configuration of the application.
 *
 * @package Prooph\Link\ProcessManager\Model\Workflow
 * @author Alexander Miertsch <kontakt@codeliner.ws>
 */
interface WorkflowPublisher 
{
    /**
     * @param Workflow $workflow
     * @return void
     */
    public function writeToProcessingConfig(Workflow $workflow);
} 