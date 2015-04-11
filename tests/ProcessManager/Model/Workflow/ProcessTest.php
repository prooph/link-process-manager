<?php
/*
 * This file is part of prooph/link.
 * (c) 2014-2015 prooph software GmbH <contact@prooph.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 * 
 * Date: 4/7/15 - 8:56 PM
 */
namespace ProophTest\Link\ProcessManager\Model\Workflow;

use Prooph\Link\ProcessManager\Model\Task\TaskId;
use Prooph\Link\ProcessManager\Model\Workflow\Process;
use Prooph\Link\ProcessManager\Model\Workflow\ProcessId;
use Prooph\Link\ProcessManager\Model\Workflow\ProcessType;
use ProophTest\Link\ProcessManager\TestCase;

final class ProcessTest extends TestCase
{
    /**
     * @test
     */
    function it_is_created_with_a_specific_process_type_and_an_empty_task_list()
    {
        $process = Process::ofType(ProcessType::linearMessaging(), ProcessId::generate());

        $this->assertTrue(ProcessType::linearMessaging()->equals($process->type()));
        $this->assertEmpty($process->tasks());
    }

    /**
     * @test
     */
    function it_can_be_reconstituted_with_a_task_list()
    {
        $task1 = TaskId::generate();
        $task2 = TaskId::generate();

        $process = Process::withTaskList([$task1, $task2], ProcessId::generate(), ProcessType::linearMessaging());

        $this->assertTrue($task1->equals($process->tasks()[0]));
        $this->assertTrue($task2->equals($process->tasks()[1]));
        $this->assertTrue(ProcessType::linearMessaging()->equals($process->type()));
    }
} 