<?php
/*
 * This file is part of prooph/link.
 * (c) 2014-2015 prooph software GmbH <contact@prooph.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 * 
 * Date: 4/12/15 - 6:11 PM
 */
namespace Prooph\Link\ProcessManager\Model\Workflow\Exception;

use Prooph\Link\ProcessManager\Model\Workflow\WorkflowId;

/**
 * Exception WorkflowNotFound
 *
 * @package Prooph\Link\ProcessManager\Model\Workflow\Exception
 * @author Alexander Miertsch <kontakt@codeliner.ws>
 */
final class WorkflowNotFound extends \InvalidArgumentException
{
    /**
     * @param WorkflowId $workflowId
     * @return WorkflowNotFound
     */
    public static function withId(WorkflowId $workflowId)
    {
        return new self(sprintf('Workflow with id %s could not be found', $workflowId->toString()));
    }
} 