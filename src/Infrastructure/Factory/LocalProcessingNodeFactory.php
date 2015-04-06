<?php
/*
 * This file is part of prooph/link.
 * (c) 2014-2015 prooph software GmbH <contact@prooph.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 * 
 * Date: 4/6/15 - 2:33 PM
 */
namespace Prooph\Link\ProcessManager\Infrastructure\Factory;

use Prooph\Link\Application\Projection\ProcessingConfig;
use Prooph\Link\ProcessManager\Model\ProcessingNode;
use Prooph\Processing\Processor\NodeName;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

/**
 * Class LocalProcessingNodeFactory
 *
 * This factory creates the local processing node by using the node name defined in the local processing configuration.
 *
 * @package Prooph\Link\ProcessManager\Infrastructure\Factory
 * @author Alexander Miertsch <kontakt@codeliner.ws>
 */
final class LocalProcessingNodeFactory implements FactoryInterface
{
    /**
     * Create service
     *
     * @param ServiceLocatorInterface $serviceLocator
     * @return mixed
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        /** @var $processingConfig ProcessingConfig */
        $processingConfig = $serviceLocator->get('processing_config');

        return ProcessingNode::initializeAs(NodeName::fromString($processingConfig->getNodeName()));
    }
}