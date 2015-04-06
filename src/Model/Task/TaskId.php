<?php
/*
 * This file is part of prooph/link.
 * (c) 2014-2015 prooph software GmbH <contact@prooph.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 * 
 * Date: 4/5/15 - 1:39 AM
 */
namespace Prooph\Link\ProcessManager\Model\Task;

use Rhumsaa\Uuid\Uuid;

/**
 * Class TaskId
 *
 * @package Prooph\Link\ProcessManager\Model\Task
 * @author Alexander Miertsch <kontakt@codeliner.ws>
 */
final class TaskId 
{
    /**
     * @var Uuid
     */
    private $uuid;

    /**
     * @return TaskId
     */
    public static function generate()
    {
        return new self(Uuid::uuid4());
    }

    /**
     * @param string $taskId
     * @return TaskId
     */
    public static function fromString($taskId)
    {
        return new self(Uuid::fromString($taskId));
    }

    private function __construct(Uuid $uuid)
    {
        $this->uuid = $uuid;
    }

    /**
     * @return string
     */
    public function toString()
    {
        return $this->uuid->toString();
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return $this->toString();
    }

    /**
     * @param TaskId $other
     * @return bool
     */
    public function equals(TaskId $other)
    {
        return $this->toString() === $other->toString();
    }
} 