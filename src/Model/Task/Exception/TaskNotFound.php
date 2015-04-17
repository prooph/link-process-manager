<?php
/*
 * This file is part of prooph/link.
 * (c) 2014-2015 prooph software GmbH <contact@prooph.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 * 
 * Date: 4/17/15 - 10:10 PM
 */
namespace Prooph\Link\ProcessManager\Model\Task\Exception;

use Prooph\Link\ProcessManager\Model\Task\TaskId;

/**
 * Exception TaskNotFound
 *
 * @package Prooph\Link\ProcessManager\Model\Task\Exception
 * @author Alexander Miertsch <kontakt@codeliner.ws>
 */
final class TaskNotFound extends \InvalidArgumentException
{
    /**
     * @param TaskId $taskId
     * @return TaskNotFound
     */
    public static function withId(TaskId $taskId)
    {
        return new self(sprintf('Task with id %s could not be found', $taskId->toString()));
    }
} 