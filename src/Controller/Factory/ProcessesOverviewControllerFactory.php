<?php
/*
* This file is part of prooph/link.
 * (c) prooph software GmbH <contact@prooph.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 * 
 * Date: 1/23/15 - 4:17 PM
 */
namespace Prooph\Link\ProcessManager\Controller\Factory;

use Prooph\Link\ProcessManager\Controller\ProcessesOverviewController;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

/**
 * Class ProcessesOverviewControllerFactory
 *
 * @package Prooph\Link\ProcessManager\Controller\Factory
 * @author Alexander Miertsch <kontakt@codeliner.ws>
 */
final class ProcessesOverviewControllerFactory implements FactoryInterface
{
    /**
     * Create service
     *
     * @param ServiceLocatorInterface $serviceLocator
     * @return mixed
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        return new ProcessesOverviewController(
            $serviceLocator->getServiceLocator()->get('prooph.link.monitor.process_logger')
        );
    }
}