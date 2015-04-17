<?php
/*
 * This file is part of prooph/link.
 * (c) 2014-2015 prooph software GmbH <contact@prooph.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 * 
 * Date: 4/17/15 - 10:40 PM
 */
namespace Prooph\Link\ProcessManager\Infrastructure\Factory;
use Prooph\Link\ProcessManager\Model\Task\UpdateTaskMetadataHandler;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

/**
 * Class UpdateTaskMetadataHandlerFactory
 *
 * @package Prooph\Link\ProcessManager\Infrastructure\Factory
 * @author Alexander Miertsch <kontakt@codeliner.ws>
 */
final class UpdateTaskMetadataHandlerFactory implements FactoryInterface
{

    /**
     * Create service
     *
     * @param ServiceLocatorInterface $serviceLocator
     * @return mixed
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        return new UpdateTaskMetadataHandler($serviceLocator->get('prooph.link.pm.task_collection'));
    }
}