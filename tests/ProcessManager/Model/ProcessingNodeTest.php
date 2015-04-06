<?php
/*
 * This file is part of prooph/link.
 * (c) 2014-2015 prooph software GmbH <contact@prooph.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 * 
 * Date: 4/5/15 - 11:27 PM
 */
namespace ProophTest\Link\ProcessManager\Model;

use Prooph\Link\ProcessManager\Model\ProcessingNode;
use Prooph\Link\ProcessManager\Model\ProcessingNodeName;
use Prooph\Link\ProcessManager\Model\Workflow\WorkflowId;
use Prooph\Processing\Processor\NodeName;
use ProophTest\Link\ProcessManager\TestCase;

final class ProcessingNodeTest extends TestCase
{
    /**
     * @test
     */
    function it_can_be_initialized_with_a_node_name()
    {
        $processingNode = ProcessingNode::initializeAs(NodeName::fromString('localhost'));

        $this->assertTrue(NodeName::fromString('localhost')->equals($processingNode->nodeName()));
    }

    /**
     * @test
     */
    function it_set_up_a_new_workflow()
    {
        $node = ProcessingNode::initializeAs(NodeName::defaultName());
        $workflowId = WorkflowId::generate();

        $workflow = $node->setUpNewWorkflow($workflowId, 'Article Export');

        $this->assertTrue(NodeName::defaultName()->equals($workflow->processingNodeName()));
        $this->assertTrue($workflowId->equals($workflow->workflowId()));
        $this->assertEquals('Article Export', $workflow->name());
    }

    /**
     * @test
     */
    function it_is_equal_to_a_node_with_the_same_name()
    {
        $processingNode1 = ProcessingNode::initializeAs(NodeName::fromString('localhost'));
        $processingNode2 = ProcessingNode::initializeAs(NodeName::fromString('localhost'));

        $this->assertTrue($processingNode1->sameNodeAs($processingNode2));
    }
} 