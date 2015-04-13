<?php
/*
 * This file is part of prooph/link.
 * (c) 2014-2015 prooph software GmbH <contact@prooph.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 * 
 * Date: 4/12/15 - 8:03 PM
 */
namespace Prooph\Link\ProcessManager\Api\Factory;
use Prooph\Link\ProcessManager\Api\Flowchart;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

/**
 * Class FlowchartFactory
 *
 * @package Prooph\Link\ProcessManager\Api\Factory
 * @author Alexander Miertsch <kontakt@codeliner.ws>
 */
final class FlowchartFactory implements FactoryInterface
{
    /**
     * Create service
     *
     * @param ServiceLocatorInterface $serviceLocator
     * @return mixed
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        return new Flowchart($serviceLocator->getServiceLocator()->get('prooph.link.pm.flowchart_store'));
    }
}