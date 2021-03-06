<?php
/*
 * This file is part of prooph/link.
 * (c) 2014-2015 prooph software GmbH <contact@prooph.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 * 
 * Date: 4/6/15 - 5:54 PM
 */
namespace Prooph\Link\ProcessManager\Model;

use Codeliner\ArrayReader\ArrayReader;
use Prooph\Link\Application\SharedKernel\MessageMetadata;
use Zend\Stdlib\ArrayUtils;

/**
 * Value Object ProcessingMetadata
 *
 * In the processing system communication happens with the help of workflow messages. The system itself and
 * also workflow message handlers can use metadata to add or read additional information to or from
 * a message. Metadata can contain arrays and scalar values of any meaning.
 * But there are also known terms which play an important role for tasks and processes. So this value object encapsulates
 * these terms to provide a Workflow with required information to choose the correct process or task type.
 *
 * @package Prooph\Link\ProcessManager\Model
 * @author Alexander Miertsch <kontakt@codeliner.ws>
 */
final class ProcessingMetadata 
{
    /**
     * @var ArrayReader
     */
    private $metadata;

    /**
     * @param array $metadata
     * @return ProcessingMetadata
     */
    public static function fromArray(array $metadata)
    {
        return new self($metadata);
    }

    /**
     * @return ProcessingMetadata
     */
    public static function noData()
    {
        return new self([]);
    }

    /**
     * @param array $metadata
     */
    private function __construct(array $metadata)
    {
        $this->setMetadata($metadata);
    }

    /**
     * Merges given metadata recursive into existing metadata and returns a new ProcessingMetadata object
     *
     * @param ProcessingMetadata $metadata
     * @return ProcessingMetadata
     */
    public function merge(ProcessingMetadata $metadata)
    {
        return new self(ArrayUtils::merge($this->metadata->toArray(), $metadata->toArray()));
    }

    /**
     * @return array
     */
    public function toArray()
    {
        return $this->metadata->toArray();
    }

    /**
     * Metadata of a message handler should contain the "chunk_support" flag
     * if the handler can collect and/or process chunks.
     *
     * @return bool
     */
    public function canHandleChunks()
    {
        return $this->metadata->booleanValue('chunk_support');
    }

    /**
     * If a limit is present in a task metadata and this limit is greater than zero then the client wants the system
     * to process a source collection in chunks.
     *
     * @return bool
     */
    public function shouldCollectionBeSplitIntoChunks()
    {
        return $this->metadata->integerValue(MessageMetadata::LIMIT) > 0;
    }

    /**
     * Assert that metadata only contains scalar values and arrays
     *
     * @param array $metadata
     */
    private function setMetadata(array $metadata)
    {
        foreach($metadata as $key => &$partial) {
            $this->assertArrayOrScalar($partial, $key);
        }

        $this->metadata = new ArrayReader($metadata);
    }

    /**
     * Recursive assertion of metadata
     *
     * @param mixed $partialMetadata
     * @param string $partialKey
     * @throws \InvalidArgumentException
     */
    private function assertArrayOrScalar(&$partialMetadata, $partialKey)
    {
        if (is_scalar($partialMetadata)) return;

        if (! is_array($partialMetadata)) {
            throw new \InvalidArgumentException(sprintf(
                'The metadata key %s contains an invalid data type. Allowed types are all scalar types and arrays. Got %s',
                $partialKey,
                (is_object($partialMetadata)? get_class($partialMetadata) : gettype($partialMetadata))
            ));
        }

        foreach ($partialMetadata as $subKey => &$subPartial) {
            $this->assertArrayOrScalar($subPartial, $subKey);
        }
    }
} 