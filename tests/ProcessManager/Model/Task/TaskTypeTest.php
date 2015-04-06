<?php
/*
 * This file is part of prooph/link.
 * (c) 2014-2015 prooph software GmbH <contact@prooph.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 * 
 * Date: 4/6/15 - 5:36 PM
 */
namespace ProophTest\Link\ProcessManager\Model\Task;


use Prooph\Link\ProcessManager\Model\Task\TaskType;
use ProophTest\Link\ProcessManager\TestCase;

final class TaskTypeTest extends TestCase
{
    /**
     * @test
     */
    function it_can_be_a_collect_data_task()
    {
        $taskType = TaskType::collectData();

        $this->assertTrue($taskType->isCollectData());
        $this->assertEquals(TaskType::TYPE_COLLECT_DATA, $taskType->toString());
    }

    /**
     * @test
     */
    function it_can_be_a_process_data_task()
    {
        $taskType = TaskType::processData();

        $this->assertTrue($taskType->isProcessData());
        $this->assertEquals(TaskType::TYPE_PROCESS_DATA, $taskType->toString());
    }

    /**
     * @test
     */
    function it_can_be_a_run_sub_process_task()
    {
        $taskType = TaskType::runSubProcess();

        $this->assertTrue($taskType->isRunSubProcess());
        $this->assertEquals(TaskType::TYPE_RUN_SUB_PROCESS, $taskType->toString());
    }

    /**
     * @test
     */
    function it_can_be_a_manipulate_payload_task()
    {
        $taskType = TaskType::manipulatePayload();

        $this->assertTrue($taskType->isManipulatePayload());
        $this->assertEquals(TaskType::TYPE_MANIPULATE_PAYLOAD, $taskType->toString());
    }

    /**
     * @test
     */
    function it_is_equal_to_a_similar_task_type()
    {
        $taskType1 = TaskType::manipulatePayload();
        $taskType2 = TaskType::manipulatePayload();

        $this->assertTrue($taskType1->equals($taskType2));
    }
} 