<?php
/*
 * This file is part of prooph/link.
 * (c) 2014-2015 prooph software GmbH <contact@prooph.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 * 
 * Date: 4/6/15 - 6:26 PM
 */
namespace ProophTest\Link\ProcessManager\Model;

use MyProject\Proxies\__CG__\OtherProject\Proxies\__CG__\stdClass;
use Prooph\Link\ProcessManager\Model\ProcessingMetadata;
use ProophTest\Link\ProcessManager\TestCase;

final class ProcessingMetadataTest extends TestCase
{
    /**
     * @test
     */
    function it_can_be_created_from_array()
    {
        $metadata = ProcessingMetadata::fromArray(['meta' => 'data']);

        $this->assertInstanceOf(ProcessingMetadata::class, $metadata);
        $this->assertEquals(['meta' => 'data'], $metadata->toArray());
    }

    /**
     * @test
     */
    function it_merges_given_metadata_with_own_metadata_and_returns_a_new_metadata_container()
    {
        $metadata = ProcessingMetadata::fromArray(['meta' => 'data']);

        $orgMetadata = clone $metadata;

        $mergedMetadata = $metadata->merge(ProcessingMetadata::fromArray(['merged' => ['meta' => 'data']]));

        $this->assertEquals(['meta' => 'data', 'merged' => ['meta' => 'data']], $mergedMetadata->toArray());
        $this->assertEquals($orgMetadata->toArray(), $metadata->toArray());
    }

    /**
     * @test
     * @dataProvider provideValidMetadata
     */
    function it_allows_arrays_and_scalar_values_of_any_deep($metadata)
    {
        $meta = ProcessingMetadata::fromArray($metadata);

        $this->assertEquals($metadata, $meta->toArray());
    }

    /**
     * @test
     * @dataProvider provideNonValidMetadata
     * @expectedException \InvalidArgumentException
     */
    function it_does_not_allow_non_scalar_and_non_array_values($metadata)
    {
        ProcessingMetadata::fromArray($metadata);
    }

    /**
     * @return array
     */
    public function provideValidMetadata()
    {
        return [
            [
                ['string' => 'data', 'number' => 1, 'float' => 1.1, 'bool' => true],
            ],
            [
                ['array' => ['sub_array' => ['string' => 'data']]],
            ],
            [
                ['string' => 'data', 'array' => ['float' => 1.1], 'array2' => ['sub_array' => ['bool' => false]]],
            ]
        ];
    }

    /**
     * @return array
     */
    public function provideNonValidMetadata()
    {
        return [
            [
                ['object' => new \stdClass()],
            ],
            [
                ['array' => ['object' => new \stdClass()]],
            ],
            [
                ['null' => null],
            ],
            [
                ['string' => 'data', ['array' => ['sub_array' => ['null' => null]]]],
            ]
        ];
    }
} 