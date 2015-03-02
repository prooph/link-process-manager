<?php
/*
* This file is part of prooph/link.
 * (c) prooph software GmbH <contact@prooph.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 * 
 * Date: 01.01.15 - 18:29
 */

namespace Prooph\Link\ProcessManager\Api\Factory;

use Prooph\Link\Application\SharedKernel\ScriptLocation;
use Prooph\Link\ProcessManager\Api\Process;
use Prooph\Link\Application\Definition;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

/**
 * Class ProcessFactory
 *
 * @package ProcessConfig\Api\Factory
 * @author Alexander Miertsch <kontakt@codeliner.ws>
 */
final class ProcessFactory implements FactoryInterface
{

    /**
     * Create service
     *
     * @param ServiceLocatorInterface $serviceLocator
     * @return mixed
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        $process = new Process();

        $process->setScriptLocation(ScriptLocation::fromPath(Definition::getScriptsDir()));

        return $process;
    }
}
 