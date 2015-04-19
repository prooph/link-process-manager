<?php
/*
 * This file is part of prooph/link.
 * (c) 2014-2015 prooph software GmbH <contact@prooph.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 * 
 * Date: 4/19/15 - 6:34 PM
 */
namespace Prooph\Link\ProcessManager\Infrastructure\Factory;
use Prooph\Link\ProcessManager\Infrastructure\WorkflowToProcessingConfigTranslator;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

/**
 * Factory WorkflowPublisherFactory
 *
 * @package Prooph\Link\ProcessManager\Infrastructure\Factory
 * @author Alexander Miertsch <kontakt@codeliner.ws>
 */
final class WorkflowPublisherFactory implements FactoryInterface
{

    /**
     * Create service
     *
     * @param ServiceLocatorInterface $serviceLocator
     * @return mixed
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        return new WorkflowToProcessingConfigTranslator(
            $serviceLocator->get('prooph.link.pm.task_collection'),
            $serviceLocator->get('prooph.link.pm.message_handler_collection'),
            $serviceLocator->get('prooph.link.system_config'),
            $serviceLocator->get('prooph.psb.command_bus'),
            $serviceLocator->get('prooph.link.app.config_location')
        );
    }
}