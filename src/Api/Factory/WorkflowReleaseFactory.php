<?php
/*
 * This file is part of prooph/link.
 * (c) 2014-2015 prooph software GmbH <contact@prooph.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 * 
 * Date: 4/19/15 - 12:47 PM
 */
namespace Prooph\Link\ProcessManager\Api\Factory;

use Prooph\Link\ProcessManager\Api\WorkflowRelease;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

/**
 * Resource Factory WorkflowReleaseFactory
 *
 * @package Prooph\Link\ProcessManager\Api\Factory
 * @author Alexander Miertsch <kontakt@codeliner.ws>
 */
final class WorkflowReleaseFactory implements FactoryInterface
{
    /**
     * Create service
     *
     * @param ServiceLocatorInterface $serviceLocator
     * @return WorkflowRelease
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        $resource = new WorkflowRelease();

        $resource->setWorkflowFinder($serviceLocator->getServiceLocator()->get(\Prooph\Link\ProcessManager\Projection\Workflow\WorkflowFinder::class));

        return $resource;
    }
}