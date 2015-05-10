<?php
/*
* This file is part of prooph/link.
 * (c) prooph software GmbH <contact@prooph.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 * 
 * Date: 20.01.15 - 22:26
 */

namespace Prooph\Link\ProcessManager\Controller;

use Prooph\Link\Application\Service\TranslatorAwareController;
use Prooph\Link\Dashboard\Controller\AbstractWidgetController;
use Prooph\Link\Dashboard\View\DashboardWidget;
use Prooph\Link\ProcessManager\Model\ProcessLogger;
use Zend\Mvc\I18n\Translator;
use Zend\View\Model\ViewModel;

/**
 * Class ProcessesOverviewController
 *
 * @package Prooph\Link\ProcessManager\Controller
 * @author Alexander Miertsch <kontakt@codeliner.ws>
 */
final class ProcessesOverviewController extends AbstractWidgetController implements TranslatorAwareController
{
    /**
     * @var ProcessLogger
     */
    private $processLogger;

    /**
     * @var Translator
     */
    private $translator;

    /**
     * @param ProcessLogger $processLogger
     */
    public function __construct(ProcessLogger $processLogger)
    {
        $this->processLogger = $processLogger;
    }

    /**
     * @return DashboardWidget
     */
    public function widgetAction()
    {
        $lastLoggedProcesses = $this->processLogger->getLastLoggedProcesses(0, 3);

        if (empty($lastLoggedProcesses)) return false;

        $this->addProcessNames($lastLoggedProcesses);

        return DashboardWidget::initialize(
            $this->widgetConfig->get('template', 'prooph/link/monitor/process-view/partial/process-list'),
            $this->widgetConfig->get('title', $this->translator->translate('Workflow Monitor')),
            $this->widgetConfig->get('cols', 12),
            ['processes' => $lastLoggedProcesses],
            $this->widgetConfig->get('group_title')
        );
    }

    /**
     * @return ViewModel
     */
    public function overviewAction()
    {
        $lastLoggedProcesses = $this->processLogger->getLastLoggedProcesses(0, 10);

        $this->addProcessNames($lastLoggedProcesses);

        $view = new ViewModel(['processes' => $lastLoggedProcesses]);

        $view->setTemplate('prooph/link/monitor/process-view/overview');

        return $view;
    }

    /**
     * @param array $processLogEntries
     */
    private function addProcessNames(array &$processLogEntries)
    {
        $processDefinitions = $this->systemConfig->getProcessDefinitions();

        foreach ($processLogEntries as &$processLogEntry) {
            if (isset($processDefinitions[$processLogEntry['start_message']])) {

                $processLogEntry['process_name'] = $processDefinitions[$processLogEntry['start_message']]['name'];
            } else {
                $processLogEntry['process_name'] = $this->translator->translate('Unknown');
            }
        }
    }

    /**
     * @param Translator $translator
     * @return void
     */
    public function setTranslator(Translator $translator)
    {
        $this->translator = $translator;
    }
}
 