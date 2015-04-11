<?php
/*
 * This file is part of prooph/link.
 * (c) 2014-2015 prooph software GmbH <contact@prooph.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 * 
 * Date: 4/11/15 - 10:51 PM
 */
namespace Prooph\Link\ProcessManager\Model;

use Prooph\Processing\Processor\NodeName;

/**
 * Class ProcessingNodeList
 *
 * @package Prooph\Link\ProcessManager\Model
 * @author Alexander Miertsch <kontakt@codeliner.ws>
 */
final class ProcessingNodeList 
{
    /**
     * @param NodeName $nodeName
     * @return ProcessingNode
     */
    public function getNode(NodeName $nodeName)
    {
        //@TODO: Connect node list with configuration to assert node name
        return ProcessingNode::initializeAs($nodeName);
    }
} 