<?php
/*
 * This file is part of prooph/link.
 * (c) 2014-2015 prooph software GmbH <contact@prooph.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 * 
 * Date: 4/6/15 - 8:50 PM
 */
namespace ProophTest\Link\ProcessManager\Mock\ProcessingType;

use Prooph\Processing\Type\AbstractCollection;
use Prooph\Processing\Type\Description\Description;
use Prooph\Processing\Type\Description\NativeType;
use Prooph\Processing\Type\Prototype;

final class ArticleCollection extends AbstractCollection
{

    /**
     * Returns the prototype of the items type
     *
     * A collection has always one property with name item representing the type of all items in the collection.
     *
     * @return Prototype
     */
    public static function itemPrototype()
    {
        return Article::prototype();
    }

    /**
     * @return Description
     */
    public static function buildDescription()
    {
        return new Description('Article List', NativeType::COLLECTION, false);
    }
}