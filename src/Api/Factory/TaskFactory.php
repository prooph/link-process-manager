<?php
/*
 * This file is part of prooph/link.
 * (c) 2014-2015 prooph software GmbH <contact@prooph.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 * 
 * Date: 4/17/15 - 7:00 PM
 */
namespace Prooph\Link\ProcessManager\Api\Factory;
use Prooph\Link\ProcessManager\Api\Task;
use Prooph\Link\ProcessManager\Projection\Task\TaskFinder;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

/**
 * Class TaskFactory
 *
 * @package Prooph\Link\ProcessManager\Api\Factory
 * @author Alexander Miertsch <kontakt@codeliner.ws>
 */
final class TaskFactory implements FactoryInterface
{
    /**
     * Create service
     *
     * @param ServiceLocatorInterface $serviceLocator
     * @return mixed
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        $taskResource = new Task();
        $taskResource->setTaskFinder($serviceLocator->getServiceLocator()->get(TaskFinder::class));
        return $taskResource;
    }
}