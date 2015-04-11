<?php
/*
 * This file is part of prooph/link.
 * (c) 2014-2015 prooph software GmbH <contact@prooph.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 * 
 * Date: 4/11/15 - 11:11 PM
 */
namespace Prooph\Link\ProcessManager\Infrastructure\Factory;

use Prooph\Link\ProcessManager\Infrastructure\PESMessageHandlerCollection;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

/**
 * Class MessageHandlerCollectionFactory
 *
 * @package Prooph\Link\ProcessManager\Infrastructure\Factory
 * @author Alexander Miertsch <alexander.miertsch.extern@sixt.com>
 */
final class MessageHandlerCollectionFactory implements FactoryInterface
{

    /**
     * Create service
     *
     * @param ServiceLocatorInterface $serviceLocator
     * @return mixed
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        return new PESMessageHandlerCollection($serviceLocator->get('prooph.event_store'));
    }
}