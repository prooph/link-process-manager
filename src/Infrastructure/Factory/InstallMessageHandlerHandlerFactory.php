<?php
/*
 * This file is part of prooph/link.
 * (c) 2014-2015 prooph software GmbH <contact@prooph.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 * 
 * Date: 4/11/15 - 11:17 PM
 */
namespace Prooph\Link\ProcessManager\Infrastructure\Factory;

use Prooph\Link\ProcessManager\Model\MessageHandler\InstallMessageHandlerHandler;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

/**
 * Factory InstallMessageHandlerHandlerFactory
 *
 * @package Prooph\Link\ProcessManager\Infrastructure\Factory
 * @author Alexander Miertsch <kontakt@prooph.de>
 */
final class InstallMessageHandlerHandlerFactory implements FactoryInterface
{
    /**
     * Create service
     *
     * @param ServiceLocatorInterface $serviceLocator
     * @return mixed
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        return new InstallMessageHandlerHandler(
            $serviceLocator->get('prooph.link.pm.processing_node_list'),
            $serviceLocator->get('prooph.link.pm.message_handler_collection')
        );
    }
}