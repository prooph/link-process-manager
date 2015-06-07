<?php
/*
 * This file is part of prooph/link.
 * (c) 2014-2015 prooph software GmbH <contact@prooph.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 * 
 * Date: 5/25/15 - 9:27 PM
 */
namespace Prooph\Link\ProcessManager\Api\Factory;
use Prooph\Link\Application\Definition;
use Prooph\Link\Application\SharedKernel\ScriptLocation;
use Prooph\Link\ProcessManager\Api\Log;
use Prooph\Link\ProcessManager\Projection\Log\ProcessLogFinder;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

/**
 * Class LogResourceFactory
 *
 * @package Prooph\Link\ProcessManager\Api\Factory
 * @author Alexander Miertsch <kontakt@codeliner.ws>
 */
final class LogResourceFactory implements FactoryInterface
{
    /**
     * Create service
     *
     * @param ServiceLocatorInterface $serviceLocator
     * @return mixed
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        return new Log(
            $serviceLocator->getServiceLocator()->get(ProcessLogFinder::class),
            $serviceLocator->getServiceLocator()->get('prooph.link.pm.process_stream_reader'),
            $serviceLocator->getServiceLocator()->get('processing_config'),
            ScriptLocation::fromPath(Definition::getScriptsDir())
        );

    }
}