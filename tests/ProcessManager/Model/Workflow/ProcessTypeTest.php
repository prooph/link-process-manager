<?php
/*
 * This file is part of prooph/link.
 * (c) 2014-2015 prooph software GmbH <contact@prooph.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 * 
 * Date: 4/6/15 - 4:45 PM
 */
namespace ProophTest\Link\ProcessManager\Model\Workflow;


use Prooph\Link\ProcessManager\Model\Workflow\ProcessType;
use ProophTest\Link\ProcessManager\TestCase;

final class ProcessTypeTest extends TestCase
{
    /**
     * @test
     */
    function it_can_be_a_linear_messaging_type()
    {
        $type = ProcessType::linearMessaging();

        $this->assertTrue($type->isLinearMessaging());
        $this->assertEquals(ProcessType::TYPE_LINEAR_MESSAGING, $type->toString());
    }

    /**
     * @test
     */
    function it_can_be_a_parallel_for_each_type()
    {
        $type = ProcessType::parallelForeach();

        $this->assertTrue($type->isParallelForeach());
        $this->assertEquals(ProcessType::TYPE_PARALLEL_FOREACH, $type->toString());
    }

    /**
     * @test
     */
    function it_can_be_a_parallel_chunk_type()
    {
        $type = ProcessType::parallelChunk();

        $this->assertTrue($type->isParallelChunk());
        $this->assertEquals(ProcessType::TYPE_PARALLEL_CHUNK, $type->toString());
    }

    /**
     * @test
     */
    function it_can_be_a_parallel_fork_type()
    {
        $type = ProcessType::parallelFork();

        $this->assertTrue($type->isParallelFork());
        $this->assertEquals(ProcessType::TYPE_PARALLEL_FORK, $type->toString());
    }

    /**
     * @test
     */
    function it_is_equal_to_a_similar_type()
    {
        $type1 = ProcessType::parallelChunk();
        $type2 = ProcessType::parallelChunk();

        $this->assertTrue($type1->equals($type2));
    }
} 