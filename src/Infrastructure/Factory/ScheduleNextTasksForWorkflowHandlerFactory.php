<?php
/*
 * This file is part of prooph/link.
 * (c) 2014-2015 prooph software GmbH <contact@prooph.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 * 
 * Date: 4/18/15 - 7:48 PM
 */
namespace Prooph\Link\ProcessManager\Infrastructure\Factory;

use Prooph\Link\ProcessManager\Model\Workflow\ScheduleNextTasksForWorkflowHandler;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

/**
 * Factory ScheduleNextTasksForWorkflowHandlerFactory
 *
 * @package Prooph\Link\ProcessManager\Infrastructure\Factory
 * @author Alexander Miertsch <kontakt@codeliner.ws>
 */
final class ScheduleNextTasksForWorkflowHandlerFactory implements FactoryInterface
{
    /**
     * Create service
     *
     * @param ServiceLocatorInterface $serviceLocator
     * @return mixed
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        return new ScheduleNextTasksForWorkflowHandler(
            $serviceLocator->get('prooph.link.pm.workflow_collection'),
            $serviceLocator->get('prooph.link.pm.task_collection'),
            $serviceLocator->get('prooph.link.pm.message_handler_collection')
        );
    }
}