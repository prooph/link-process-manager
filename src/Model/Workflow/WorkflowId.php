<?php
/*
 * This file is part of prooph/link.
 * (c) 2014-2015 prooph software GmbH <contact@prooph.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 * 
 * Date: 4/3/15 - 11:39 PM
 */
namespace Prooph\Link\ProcessManager\Model\Workflow;

use Rhumsaa\Uuid\Uuid;

/**
 * Value Object WorkflowId
 *
 * Global Unique Identifier of a Workflow
 *
 * @package Prooph\Link\ProcessManager\Model\Workflow
 * @author Alexander Miertsch <kontakt@codeliner.ws>
 */
final class WorkflowId 
{
    /**
     * @var Uuid
     */
    private $uuid;

    /**
     * @return WorkflowId
     */
    public static function generate()
    {
        return new self(Uuid::uuid4());
    }

    /**
     * @param string $workflowId
     * @return WorkflowId
     */
    public static function fromString($workflowId)
    {
        return new self(Uuid::fromString($workflowId));
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
     * @param WorkflowId $other
     * @return bool
     */
    public function equals(WorkflowId $other)
    {
        return $this->toString() === $other->toString();
    }
}