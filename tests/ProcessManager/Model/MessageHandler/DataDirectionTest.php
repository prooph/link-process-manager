<?php
/*
 * This file is part of prooph/link.
 * (c) 2014-2015 prooph software GmbH <contact@prooph.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 * 
 * Date: 4/5/15 - 7:54 PM
 */
namespace ProophTest\Link\ProcessManager\Model\MessageHandler;

use Prooph\Link\ProcessManager\Model\MessageHandler\DataDirection;
use ProophTest\Link\ProcessManager\TestCase;

final class DataDirectionTest extends TestCase
{
    /**
     * @test
     */
    function it_can_be_a_source()
    {
        $source = DataDirection::source();

        $this->assertTrue($source->isSource());
        $this->assertFalse($source->isTarget());
        $this->assertEquals(DataDirection::DIRECTION_SOURCE, $source->toString());
    }

    /**
     * @test
     */
    function it_can_be_a_target()
    {
        $target = DataDirection::target();

        $this->assertTrue($target->isTarget());
        $this->assertFalse($target->isSource());

        $this->assertEquals(DataDirection::DIRECTION_TARGET, $target->toString());
    }

    /**
     * @test
     */
    function it_can_be_a_source_and_a_target()
    {
        $both = DataDirection::both();

        $this->assertTrue($both->isSource());
        $this->assertTrue($both->isTarget());

        $this->assertEquals(DataDirection::DIRECTION_SOURCE_AND_TARGET, $both->toString());
    }

    /**
     * @test
     */
    function it_is_equal_to_another_source()
    {
        $direction1 = DataDirection::source();
        $direction2 = DataDirection::source();

        $this->assertTrue($direction1->equals($direction2));
    }
} 