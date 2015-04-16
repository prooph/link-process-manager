<?php
/*
 * This file is part of prooph/link.
 * (c) 2014-2015 prooph software GmbH <contact@prooph.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 * 
 * Date: 4/16/15 - 6:38 PM
 */

namespace Prooph\Link\ProcessManager\Model\Task;
use Prooph\Link\ProcessManager\Model\Task;

/**
 * Interface TaskCollection
 *
 * @package Prooph\Link\ProcessManager\Model\Task
 * @author Alexander Miertsch <kontakt@codeliner.ws>
 */
interface TaskCollection 
{
    /**
     * @param TaskId $taskId
     * @return Task
     */
    public function get(TaskId $taskId);

    /**
     * @param Task $task
     * @return void
     */
    public function add(Task $task);
} 