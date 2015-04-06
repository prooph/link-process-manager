<?php
/*
 * This file is part of prooph/link.
 * (c) 2014-2015 prooph software GmbH <contact@prooph.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 * 
 * Date: 4/6/15 - 4:57 PM
 */
namespace Prooph\Link\ProcessManager\Model;

/**
 * Class Task
 *
 * In the process manager domain a task is an own aggregate because it needs to be modified by the client independent of
 * its process. But the task keeps a reference to the process so that it can be assigned to it when the whole workflow
 * is ported to the processing configuration.
 *
 * @package Prooph\Link\ProcessManager\Model
 * @author Alexander Miertsch <kontakt@codeliner.ws>
 */
final class Task 
{

} 