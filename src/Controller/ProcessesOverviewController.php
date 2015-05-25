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
use Prooph\Link\ProcessManager\Projection\Log\ProcessLogFinder;
use Prooph\Link\ProcessManager\Projection\Log\ProcessLogFormatter;
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
     * @var ProcessLogFinder
     */
    private $processLogFinder;

    /**
     * @var Translator
     */
    private $translator;

    /**
     * @param ProcessLogFinder $processLogFinder
     */
    public function __construct(ProcessLogFinder $processLogFinder)
    {
        $this->processLogFinder = $processLogFinder;
    }

    /**
     * @return DashboardWidget
     */
    public function widgetAction()
    {
        $lastLoggedProcesses = $this->processLogFinder->getLastLoggedProcesses(0, 3);

        if (empty($lastLoggedProcesses)) return false;

        ProcessLogFormatter::formatProcessLogs($lastLoggedProcesses, $this->systemConfig, $this->translator);

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
        $lastLoggedProcesses = $this->processLogFinder->getLastLoggedProcesses(0, 10);

        ProcessLogFormatter::formatProcessLogs($lastLoggedProcesses, $this->systemConfig, $this->translator);

        $view = new ViewModel(['processes' => $lastLoggedProcesses]);

        $view->setTemplate('prooph/link/monitor/process-view/overview');

        return $view;
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
 