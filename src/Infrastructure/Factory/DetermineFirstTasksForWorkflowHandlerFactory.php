<?php
/*
 * This file is part of prooph/link.
 * (c) 2014-2015 prooph software GmbH <contact@prooph.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 * 
 * Date: 4/17/15 - 12:30 AM
 */
namespace Prooph\Link\ProcessManager\Infrastructure\Factory;
use Prooph\Link\ProcessManager\Model\Workflow\DetermineFirstTasksForWorkflowHandler;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

/**
 * Class DetermineFirstTasksForWorkflowHandlerFactory
 *
 * @package Prooph\Link\ProcessManager\Infrastructure\Factory
 * @author Alexander Miertsch <kontakt@codeliner.ws>
 */
final class DetermineFirstTasksForWorkflowHandlerFactory implements FactoryInterface
{

    /**
     * Create service
     *
     * @param ServiceLocatorInterface $serviceLocator
     * @return mixed
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        return new DetermineFirstTasksForWorkflowHandler(
            $serviceLocator->get('prooph.link.pm.workflow_collection'),
            $serviceLocator->get('prooph.link.pm.message_handler_collection'),
            $serviceLocator->get('prooph.link.pm.task_collection')
        );
    }
}