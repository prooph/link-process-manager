<?php
/*
 * This file is part of prooph/link.
 * (c) 2014-2015 prooph software GmbH <contact@prooph.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 * 
 * Date: 4/19/15 - 6:47 PM
 */
namespace Prooph\Link\ProcessManager\Infrastructure\Factory;

use Prooph\Link\ProcessManager\Model\Workflow\PublishWorkflowHandler;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

/**
 * Factory PublishWorkflowHandlerFactory
 *
 * @package Prooph\Link\ProcessManager\Infrastructure\Factory
 * @author Alexander Miertsch <kontakt@codeliner.ws>
 */
final class PublishWorkflowHandlerFactory implements FactoryInterface
{
    /**
     * Create service
     *
     * @param ServiceLocatorInterface $serviceLocator
     * @return mixed
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        return new PublishWorkflowHandler(
            $serviceLocator->get('prooph.link.pm.workflow_collection'),
            $serviceLocator->get('prooph.link.pm.workflow_publisher')
        );
    }
}