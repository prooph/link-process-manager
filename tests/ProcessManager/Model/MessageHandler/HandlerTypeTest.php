<?php
/*
 * This file is part of prooph/link.
 * (c) 2014-2015 prooph software GmbH <contact@prooph.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 * 
 * Date: 4/5/15 - 8:05 PM
 */
namespace ProophTest\Link\ProcessManager\Model\MessageHandler;

use Prooph\Link\ProcessManager\Model\MessageHandler\HandlerType;
use ProophTest\Link\ProcessManager\TestCase;

final class HandlerTypeTest extends TestCase
{
    /**
     * @test
     */
    function it_can_be_a_connector()
    {
        $connector = HandlerType::connector();

        $this->assertTrue($connector->isConnector());
        $this->assertEquals(HandlerType::TYPE_CONNECTOR, $connector->toString());
    }

    /**
     * @test
     */
    function it_can_be_a_script()
    {
        $script = HandlerType::script();

        $this->assertTrue($script->isScript());
        $this->assertEquals(HandlerType::TYPE_SCRIPT, $script->toString());
    }

    /**
     * @test
     */
    function it_can_be_a_callback()
    {
        $callback = HandlerType::callback();

        $this->assertTrue($callback->isCallback());
        $this->assertEquals(HandlerType::TYPE_CALLBACK, $callback->toString());
    }

    /**
     * @test
     */
    function it_is_equal_to_another_script()
    {
        $type1 = HandlerType::script();
        $type2 = HandlerType::script();

        $this->assertTrue($type1->equals($type2));
    }
} 