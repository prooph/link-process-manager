<?php
/*
 * This file is part of prooph/link.
 * (c) 2014-2015 prooph software GmbH <contact@prooph.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 * 
 * Date: 4/19/15 - 1:44 PM
 */
namespace Prooph\Link\ProcessManager\Model\Workflow\Exception;
use Prooph\Link\ProcessManager\Model\Workflow;

/**
 * Exception WorkflowReleaseAlreadyExists
 *
 * @package Prooph\Link\ProcessManager\Model\Workflow\Exception
 * @author Alexander Miertsch <kontakt@codeliner.ws>
 */
final class WorkflowReleaseAlreadyExists extends \RuntimeException
{
    /**
     * @param int $desiredReleaseNumber
     * @param Workflow $workflow
     * @return WorkflowReleaseAlreadyExists
     */
    public static function withReleaseNumber($desiredReleaseNumber, Workflow $workflow)
    {
        return new self(sprintf(
            'A workflow release for workflow %s (%s) with the release number %s already exists',
            $workflow->name(),
            $workflow->workflowId()->toString(),
            $desiredReleaseNumber
        ));
    }
} 