<?php
/*
* This file is part of prooph/link.
 * (c) prooph software GmbH <contact@prooph.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 * 
 * Date: 10.12.14 - 19:35
 */

namespace Prooph\Link\ProcessManager\Controller;

use Prooph\Common\Messaging\DomainEvent;
use Prooph\Link\Application\Service\AbstractQueryController;
use Prooph\Link\Application\Service\TranslatorAwareController;
use Prooph\Link\Application\SharedKernel\LocationTranslator;
use Prooph\Link\Application\SharedKernel\ProcessToClientTranslator;
use Prooph\Link\Application\SharedKernel\ScriptLocation;
use Prooph\Link\ProcessManager\Model\ProcessLogger;
use Prooph\Link\ProcessManager\Projection\Log\ProcessLogFinder;
use Prooph\Link\ProcessManager\Projection\Log\ProcessLogFormatter;
use Prooph\Link\ProcessManager\Projection\Process\ProcessStreamReader;
use Prooph\Link\ProcessManager\Projection\Workflow\WorkflowFinder;
use Prooph\Processing\Functional\Func;
use Prooph\Processing\Processor\ProcessId;
use Prooph\Processing\Processor\Task\TaskListPosition;
use Verraes\ClassFunctions\ClassFunctions;
use Zend\Mvc\I18n\Translator;
use ZF\ContentNegotiation\ViewModel;

/**
 * Class ProcessManagerController
 *
 * @package ProcessConfig\Controller
 * @author Alexander Miertsch <kontakt@codeliner.ws>
 */
final class ProcessManagerController extends AbstractQueryController implements TranslatorAwareController
{
    /**
     * @var WorkflowFinder
     */
    private $workflowFinder;

    /**
     * @var ScriptLocation
     */
    private $scriptLocation;

    /**
     * @var LocationTranslator
     */
    private $locationTranslator;

    /**
     * @var Translator
     */
    private $i18nTranslator;

    public function startAppAction()
    {
        $workflows = $this->workflowFinder->findAll();

        $viewModel = new ViewModel([
            'workflows' => $workflows,
            'processes' => array_values(Func::map(
                $this->systemConfig->getProcessDefinitions(),
                function($definition, $message) {
                    return $this->convertToClientProcess($message, $definition, $this->systemConfig->getAllAvailableProcessingTypes());
                }
            )),
            'connectors' => array_values(
                Func::map($this->systemConfig->getConnectors(), function ($connector, $id) {
                    $connector['id'] = $id;
                    if (!isset($connector['metadata']) || empty($connector['metadata'])) {
                        //Force empty object
                        $connector['metadata'] = new \stdClass();
                    }
                    return $connector;
                })
            ),
            'available_processing_types' => $this->getProcessingTypesForClient(),
            'available_manipulation_scripts' => $this->scriptLocation->getScriptNames(),
            'locations'  => $this->locationTranslator->getLocations(),
            'available_process_types' => [
                [
                    'value' => \Prooph\Processing\Processor\Definition::PROCESS_LINEAR_MESSAGING,
                    'label' => $this->i18nTranslator->translate('Linear Process'),
                ],
                [
                    'value' => \Prooph\Processing\Processor\Definition::PROCESS_PARALLEL_FOR_EACH,
                    'label' => $this->i18nTranslator->translate('Foreach Process'),
                ],
            ],
            'available_task_types' => [
                [
                    'value' => \Prooph\Processing\Processor\Definition::TASK_COLLECT_DATA,
                    'label' => $this->i18nTranslator->translate('Collect Data'),
                ],
                [
                    'value' => \Prooph\Processing\Processor\Definition::TASK_PROCESS_DATA,
                    'label' => $this->i18nTranslator->translate('Process Data'),
                ],
                [
                    'value' => \Prooph\Processing\Processor\Definition::TASK_MANIPULATE_PAYLOAD,
                    'label' => $this->i18nTranslator->translate('Run Manipulation Script'),
                ],
            ],
            'available_messages' => [
                [
                    'value' => 'collect-data',
                    'label' => $this->i18nTranslator->translate('Collect Data Message'),
                ],
                [
                    'value' => 'data-collected',
                    'label' => $this->i18nTranslator->translate('Data Collected Message'),
                ],
                [
                    'value' => 'process-data',
                    'label' => $this->i18nTranslator->translate('Process Data Message'),
                ],
            ],
        ]);

        $viewModel->setTemplate('prooph.link.process-manager/process-manager/app');

        $this->layout()->setVariable('includeRiotJs', true);

        return $viewModel;
    }

    /**
     * @param array $process
     * @return ViewModel
     */
    private function incompleteAction(array $process)
    {
        $view = new ViewModel(['process' => $process]);
        $view->setTemplate('prooph/link/monitor/process-view/process-details-incomplete');
        return $view;
    }

    /**
     * @param string $startMessage
     * @param array $processDefinition
     * @param array $knownProcessingTypes
     * @return array
     */
    private function convertToClientProcess($startMessage, array $processDefinition, array $knownProcessingTypes)
    {
        return ProcessToClientTranslator::translate($startMessage, $processDefinition, $knownProcessingTypes, $this->scriptLocation);
    }

    /**
     * @param ScriptLocation $scriptLocation
     */
    public function setScriptLocation(ScriptLocation $scriptLocation)
    {
        $this->scriptLocation = $scriptLocation;
    }

    /**
     * @param LocationTranslator $locationTranslator
     */
    public function setLocationTranslator(LocationTranslator $locationTranslator)
    {
        $this->locationTranslator = $locationTranslator;
    }

    /**
     * @param Translator $translator
     * @return void
     */
    public function setTranslator(Translator $translator)
    {
        $this->i18nTranslator = $translator;
    }

    /**
     * @param WorkflowFinder $workflowFinder
     */
    public function setWorkflowFinder(WorkflowFinder $workflowFinder)
    {
        $this->workflowFinder = $workflowFinder;
    }
}
 