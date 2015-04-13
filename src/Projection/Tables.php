<?php
/*
 * This file is part of prooph/link.
 * (c) 2014-2015 prooph software GmbH <contact@prooph.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 * 
 * Date: 4/13/15 - 8:12 PM
 */
namespace Prooph\Link\ProcessManager\Projection;

/**
 * Class Tables
 *
 * Defines all read tables
 *
 * @package Prooph\Link\ProcessManager\Projection
 * @author Alexander Miertsch <kontakt@codeliner.ws>
 */
final class Tables 
{
    const WORKFLOW = "link_pm_read_workflow";
    const MESSAGE_HANDLER = "link_pm_read_message_handler";
} 