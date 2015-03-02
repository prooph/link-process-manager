<?php
/*
* This file is part of prooph/link.
 * (c) prooph software GmbH <contact@prooph.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 * 
 * Date: 06.12.14 - 22:32
 */

namespace Prooph\Link\ProcessManager\Controller;

use Prooph\Link\Dashboard\Controller\AbstractWidgetController;
use Prooph\Link\Dashboard\View\DashboardWidget;
use Prooph\Link\Application\Definition;
use Prooph\Link\Application\Projection\ProcessingConfig;
use Prooph\Link\Application\Service\NeedsSystemConfig;

/**
 * Class DashboardWidgetController
 *
 * @package SystemConfig\src\Controller
 * @author Alexander Miertsch <kontakt@codeliner.ws>
 */
class DashboardWidgetController extends AbstractWidgetController
{
    /**
     * @return DashboardWidget
     */
    public function widgetAction()
    {
        if (! $this->systemConfig->isConfigured()) return false;
        if (! $this->systemConfig->isWritable())   return false;
        if (count($this->systemConfig->getConnectors()) === 0) return false;

        return DashboardWidget::initialize(
            'prooph.link.process-manager/dashboard/widget',
            'Process Manager',
            4,
            ['processingConfig' => $this->systemConfig]
        );
    }
}
 