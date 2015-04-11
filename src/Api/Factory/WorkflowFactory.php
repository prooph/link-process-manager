<?php
/*
 * This file is part of prooph/link.
 * (c) 2014-2015 prooph software GmbH <contact@prooph.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 * 
 * Date: 4/12/15 - 12:37 AM
 */
namespace Prooph\Link\ProcessManager\Api\Factory;

use Prooph\Link\ProcessManager\Api\Workflow;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

/**
 * Class WorkflowFactory
 *
 * @package Prooph\Link\ProcessManager\Api\Factory
 * @author Alexander Miertsch <kontakt@codeliner.ws>
 */
final class WorkflowFactory implements FactoryInterface
{
    /**
     * Create service
     *
     * @param ServiceLocatorInterface $serviceLocator
     * @return mixed
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        $instance = new Workflow();
        $instance->setWorkflowFinder($serviceLocator->getServiceLocator()->get(\Prooph\Link\ProcessManager\Projection\Workflow\WorkflowFinder::class));
        return $instance;
    }
}