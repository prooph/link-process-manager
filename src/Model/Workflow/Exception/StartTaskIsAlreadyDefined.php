<?php
/*
 * This file is part of prooph/link.
 * (c) 2014-2015 prooph software GmbH <contact@prooph.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 * 
 * Date: 4/6/15 - 11:16 PM
 */
namespace Prooph\Link\ProcessManager\Model\Workflow\Exception;

use Prooph\Link\ProcessManager\Model\Error\ClientError;
use Prooph\Link\ProcessManager\Model\Workflow;

/**
 * Exception StartTaskIsAlreadyDefined
 *
 * @package Prooph\Link\ProcessManager\Model\Workflow\Exception
 * @author Alexander Miertsch <kontakt@codeliner.ws>
 */
final class StartTaskIsAlreadyDefined extends \RuntimeException implements ClientError
{
    /**
     * @param Workflow $workflow
     * @return StartTaskIsAlreadyDefined
     */
    public static function forWorkflow(Workflow $workflow)
    {
        return new self(sprintf(
            'Workflow %s already has a start task',
            $workflow->name()
        ));
    }
} 