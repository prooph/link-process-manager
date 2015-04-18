<?php
/*
 * This file is part of prooph/link.
 * (c) 2014-2015 prooph software GmbH <contact@prooph.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 * 
 * Date: 4/18/15 - 9:32 PM
 */
namespace Prooph\Link\ProcessManager\Model\Workflow\Exception;

use Prooph\Link\ProcessManager\Model\Task;
use Prooph\Link\ProcessManager\Model\Workflow;

/**
 * Exception TaskProcessNotFound
 *
 * @package Prooph\Link\ProcessManager\Model\Workflow\Exception
 * @author Alexander Miertsch <kontakt@codeliner.ws>
 */
final class TaskProcessNotFound extends \RuntimeException
{
    /**
     * @param Task $task
     * @param Workflow $workflow
     * @return TaskProcessNotFound
     */
    public static function of(Task $task, Workflow $workflow)
    {
        return new self(sprintf(
            "Workflow %s (%s) has no process defined which has task %s on its task list",
            $workflow->name(),
            $workflow->workflowId()->toString(),
            $task->id()->toString()
        ));
    }
} 