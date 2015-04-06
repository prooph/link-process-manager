<?php
/*
 * This file is part of prooph/link.
 * (c) 2014-2015 prooph software GmbH <contact@prooph.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 * 
 * Date: 4/5/15 - 10:36 PM
 */
namespace ProophTest\Link\ProcessManager\Model\Workflow;

use Prooph\Link\ProcessManager\Model\Workflow\MessageType;
use ProophTest\Link\ProcessManager\TestCase;

final class MessageTypeTest extends TestCase
{
    /**
     * @test
     */
    function it_can_be_collect_data_type()
    {
        $collectDataType = MessageType::collectData();

        $this->assertTrue($collectDataType->isCollectDataMessage());
        $this->assertEquals(MessageType::TYPE_COLLECT_DATA, $collectDataType->toString());
    }

    /**
     * @test
     */
    function it_can_be_a_data_collected_type()
    {
        $dataCollected = MessageType::dataCollected();

        $this->assertTrue($dataCollected->isDataCollectedMessage());
        $this->assertEquals(MessageType::TYPE_DATA_COLLECTED, $dataCollected->toString());
    }

    /**
     * @test
     */
    function it_can_be_process_data_type()
    {
        $processData = MessageType::processData();

        $this->assertTrue($processData->isProcessDataMessage());
        $this->assertEquals(MessageType::TYPE_PROCESS_DATA, $processData->toString());
    }

    /**
     * @test
     */
    function it_can_be_a_data_processed_type()
    {
        $dataProcessed = MessageType::dataProcessed();

        $this->assertTrue($dataProcessed->isDataProcessedMessage());
        $this->assertEquals(MessageType::TYPE_DATA_PROCESSED, $dataProcessed->toString());
    }

    /**
     * @test
     */
    function it_is_equal_to_another_object_of_the_same_type()
    {
        $type1 = MessageType::collectData();
        $type2 = MessageType::collectData();

        $this->assertTrue($type1->equals($type2));
    }
} 