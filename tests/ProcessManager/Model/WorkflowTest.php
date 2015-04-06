<?php
/*
 * This file is part of prooph/link.
 * (c) 2014-2015 prooph software GmbH <contact@prooph.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 * 
 * Date: 4/5/15 - 7:50 PM
 */
namespace ProophTest\Link\ProcessManager\Model;

use Prooph\Link\ProcessManager\Model\Workflow\WorkflowId;
use Prooph\Link\ProcessManager\Model\Workflow;
use Prooph\Processing\Processor\NodeName;
use ProophTest\Link\ProcessManager\TestCase;

final class WorkflowTest extends TestCase
{
    /**
     * @test
     */
    function it_creates_itself_with_a_workflow_id_and_a_name()
    {
        $nodeName = NodeName::defaultName();
        $workflowId = WorkflowId::generate();

        $workflow = Workflow::locatedOn($nodeName, $workflowId, 'Article Export');

        $this->assertTrue($nodeName->equals($workflow->processingNodeName()));
        $this->assertTrue($workflowId->equals($workflow->workflowId()));
        $this->assertEquals('Article Export', $workflow->name());
    }
} 