<?php
/*
* This file is part of prooph/link.
 * (c) prooph software GmbH <contact@prooph.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 * 
 * Date: 21.01.15 - 22:44
 */

namespace Prooph\Link\ProcessManager\Controller\Factory;

use Prooph\Link\Application\SharedKernel\ScriptLocation;
use Prooph\Link\ProcessManager\Controller\ProcessViewController;
use Prooph\Link\Application\Definition;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

/**
 * Class ProcessViewControllerFactory
 *
 * @package Prooph\Link\ProcessManager\Controller\Factory
 * @author Alexander Miertsch <kontakt@codeliner.ws>
 */
final class ProcessViewControllerFactory implements FactoryInterface
{
    /**
     * Create service
     *
     * @param ServiceLocatorInterface $serviceLocator
     * @return mixed
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        return new ProcessViewController(
            $serviceLocator->getServiceLocator()->get('prooph.link.monitor.process_logger'),
            $serviceLocator->getServiceLocator()->get('prooph.link.monitor.process_stream_reader'),
            ScriptLocation::fromPath(Definition::getScriptsDir()),
            $serviceLocator->getServiceLocator()->get('prooph.link.app.location_translator')
        );
    }
}
 