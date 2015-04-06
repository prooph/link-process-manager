<?php
/*
 * This file is part of prooph/link.
 * (c) 2014-2015 prooph software GmbH <contact@prooph.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 * 
 * Date: 4/6/15 - 3:37 PM
 */
namespace Prooph\Link\ProcessManager\Model\Workflow;
use Prooph\Link\ProcessManager\Model\Task\TaskId;

/**
 * Class Process
 *
 * The process entity is part of the workflow aggregate. It represents a processing process with a corresponding
 * process type and a task list. A process can only be modified through the workflow aggregate, because the
 * workflow decides if a task can be added to an existing process or if it is required to work with a sub process which
 * has another process type.
 *
 * @package Prooph\Link\ProcessManager\Model\Workflow
 * @author Alexander Miertsch <kontakt@codeliner.ws>
 */
final class Process 
{
    /**
     * @var ProcessId
     */
    private $processId;

    /**
     * @var ProcessType
     */
    private $processType;

    /**
     * List of tasks assigned to the process
     *
     * @var TaskId[]
     */
    private $taskList = [];

    public static function ofType(ProcessType $processType, ProcessId $processId)
    {
        return new self($processId, $processType);
    }

    /**
     * @param ProcessId $processId
     * @param ProcessType $processType
     */
    private function __construct(ProcessId $processId, ProcessType $processType)
    {
        $this->processType = $processType;
        $this->processId   = $processId;
    }

    /**
     * @return ProcessId
     */
    public function id()
    {
        return $this->processId;
    }

    /**
     * @return ProcessType
     */
    public function type()
    {
        return $this->processType;
    }

    /**
     * @param Process $other
     * @return bool
     */
    public function sameAs(Process $other)
    {
        return $this->processId->equals($other->id());
    }
}