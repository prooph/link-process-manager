<?php
/*
 * This file is part of prooph/link.
 * (c) 2014-2015 prooph software GmbH <contact@prooph.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 * 
 * Date: 4/6/15 - 8:48 PM
 */
namespace ProophTest\Link\ProcessManager\Mock\ProcessingType;

use Prooph\Processing\Type\AbstractDictionary;
use Prooph\Processing\Type\Description\Description;
use Prooph\Processing\Type\Description\NativeType;
use Prooph\Processing\Type\Integer;
use Prooph\Processing\Type\String;

final class Article extends AbstractDictionary
{

    /**
     * @return array[propertyName => Prototype]
     */
    public static function getPropertyPrototypes()
    {
        return [
            'ean' => String::prototype(),
            'name' => String::prototype(),
            'price' => Integer::prototype(),
            'currency' => String::prototype()
        ];
    }

    /**
     * @return Description
     */
    public static function buildDescription()
    {
        return new Description('Article', NativeType::DICTIONARY, true, 'ean');
    }
}