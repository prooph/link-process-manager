<?php
/*
* This file is part of prooph/link.
 * (c) prooph software GmbH <contact@prooph.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 * 
 * Date: 01.01.15 - 17:59
 */

namespace Prooph\Link\ProcessManager\Controller\Factory;

use Prooph\Link\Application\SharedKernel\ScriptLocation;
use Prooph\Link\ProcessManager\Controller\ProcessManagerController;
use Prooph\Link\Application\Definition;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

/**
 * Class ProcessManagerControllerFactory
 *
 * @package ProcessConfig\Controller\Factory
 * @author Alexander Miertsch <kontakt@codeliner.ws>
 */
final class ProcessManagerControllerFactory implements FactoryInterface
{

    /**
     * Create service
     *
     * @param ServiceLocatorInterface $serviceLocator
     * @return ProcessManagerController
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        $con = new ProcessManagerController();
        $con->setScriptLocation(ScriptLocation::fromPath(Definition::getScriptsDir()));

        $con->setLocationTranslator($serviceLocator->getServiceLocator()->get('prooph.link.app.location_translator'));

        $con->setWorkflowFinder($serviceLocator->getServiceLocator()->get(\Prooph\Link\ProcessManager\Projection\Workflow\WorkflowFinder::class));

        return $con;
    }
}
 