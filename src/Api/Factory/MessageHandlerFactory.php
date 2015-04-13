<?php
/*
 * This file is part of prooph/link.
 * (c) 2014-2015 prooph software GmbH <contact@prooph.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 * 
 * Date: 4/13/15 - 9:18 PM
 */
namespace Prooph\Link\ProcessManager\Api\Factory;

use Prooph\Link\ProcessManager\Api\MessageHandler;
use Prooph\Link\ProcessManager\Projection\MessageHandler\MessageHandlerFinder;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

/**
 * Class MessageHandlerFactory
 *
 * @package Prooph\Link\ProcessManager\Api\Factory
 * @author Alexander Miertsch <kontakt@codeliner.ws>
 */
final class MessageHandlerFactory implements FactoryInterface
{
    /**
     * Create service
     *
     * @param ServiceLocatorInterface $serviceLocator
     * @return mixed
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        $resource = new MessageHandler();

        $resource->setFinder($serviceLocator->getServiceLocator()->get(MessageHandlerFinder::class));

        return $resource;
    }
}