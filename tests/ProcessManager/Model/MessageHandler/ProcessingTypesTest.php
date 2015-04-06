<?php
/*
 * This file is part of prooph/link.
 * (c) 2014-2015 prooph software GmbH <contact@prooph.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 * 
 * Date: 4/6/15 - 8:17 PM
 */
namespace ProophTest\Link\ProcessManager\Model\MessageHandler;

use Prooph\Link\ProcessManager\Model\MessageHandler\ProcessingTypes;
use Prooph\Processing\Type\Float;
use Prooph\Processing\Type\Integer;
use Prooph\Processing\Type\String;
use ProophTest\Link\ProcessManager\TestCase;

final class ProcessingTypesTest extends TestCase
{
    /**
     * @test
     */
    function it_takes_a_list_of_supported_types()
    {
        $processingTypes = ProcessingTypes::support([
            String::prototype(),
            Integer::prototype()
        ]);

        $this->assertTrue($processingTypes->isSupported(String::prototype()));
        $this->assertTrue($processingTypes->isSupported(Integer::prototype()));
        $this->assertFalse($processingTypes->isSupported(Float::prototype()));
    }

    /**
     * @test
     */
    function it_can_support_all_processing_types()
    {
        $processingTypes = ProcessingTypes::supportAll();

        $this->assertTrue($processingTypes->isSupported(Float::prototype()));
    }

    /**
     * @test
     */
    function it_can_be_converted_to_array_and_back()
    {
        $processingTypes = ProcessingTypes::support([
            String::prototype(),
            Integer::prototype()
        ]);

        $definition = $processingTypes->toArray();
        $copiedProcessingTypes = ProcessingTypes::fromArray($definition);

        $this->assertTrue($copiedProcessingTypes->isSupported(String::prototype()));
        $this->assertTrue($copiedProcessingTypes->isSupported(Integer::prototype()));
        $this->assertFalse($copiedProcessingTypes->isSupported(Float::prototype()));

        $allProcessingTypes = ProcessingTypes::supportAll();

        $definition = $allProcessingTypes->toArray();

        $copiedProcessingTypes = ProcessingTypes::fromArray($definition);

        $this->assertTrue($copiedProcessingTypes->isSupported(Float::prototype()));
    }
} 