<?php
/*
 * This file is part of prooph/link.
 * (c) 2014-2015 prooph software GmbH <contact@prooph.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 * 
 * Date: 4/12/15 - 6:06 PM
 */
namespace Prooph\Link\ProcessManager\Infrastructure\Factory;

use Prooph\Link\ProcessManager\Model\Workflow\ChangeWorkflowNameHandler;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

/**
 * Factory ChangeWorkflowNameHandlerFactory
 *
 * @package Prooph\Link\ProcessManager\Infrastructure\Factory
 * @author Alexander Miertsch <kontakt@codeliner.ws>
 */
final class ChangeWorkflowNameHandlerFactory implements FactoryInterface
{
    /**
     * Create service
     *
     * @param ServiceLocatorInterface $serviceLocator
     * @return mixed
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        return new ChangeWorkflowNameHandler($serviceLocator->get('prooph.link.pm.workflow_collection'));
    }
}