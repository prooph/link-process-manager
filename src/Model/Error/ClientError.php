<?php
/*
 * This file is part of prooph/link.
 * (c) 2014-2015 prooph software GmbH <contact@prooph.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 * 
 * Date: 4/6/15 - 11:01 PM
 */

namespace Prooph\Link\ProcessManager\Model\Error;

/**
 * Interface ClientError
 *
 * This interface marks a exception as caused by the client. Normally such an error can be corrected by retrying the
 * request with correct information/settings.
 *
 * @package Prooph\Link\ProcessManager\Model\Workflow\Exception
 * @author Alexander Miertsch <kontakt@codeliner.ws>
 */
interface ClientError 
{
} 