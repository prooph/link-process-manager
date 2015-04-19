<?php
/*
 * This file is part of prooph/link.
 * (c) 2014-2015 prooph software GmbH <contact@prooph.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 * 
 * Date: 4/6/15 - 7:53 PM
 */
namespace Prooph\Link\ProcessManager\Model\MessageHandler;

use Assert\Assertion;
use Prooph\Processing\Type\Prototype;
use Prooph\Processing\Type\Type;

/**
 * Class ProcessingTypes
 *
 * This value object defines which processing types are supported by a message handler.
 * It allows the definition that all processing types are supported and it provides a method to test if a given
 * processing type is supported.
 *
 * @package Prooph\Link\ProcessManager\Model\MessageHandler
 * @author Alexander Miertsch <kontakt@codeliner.ws>
 */
final class ProcessingTypes 
{
    const SUPPORT_ALL = "all";

    /**
     * @var Prototype[]
     */
    private $supportedProcessingTypes = [];

    /**
     * @var bool
     */
    private $supportAllTypes = false;

    /**
     * Supported processing types can be given as processing prototypes or a list of type classes
     *
     * @param Prototype[]|array $processingTypes
     * @return ProcessingTypes
     */
    public static function support(array $processingTypes)
    {
        $prototypes = [];

        foreach ($processingTypes as $typeClassOrPrototype) {
            if ($typeClassOrPrototype instanceof Prototype) {
                $prototypes[] = $typeClassOrPrototype;
            } else {
                Assertion::string($typeClassOrPrototype);
                Assertion::classExists($typeClassOrPrototype);
                Assertion::implementsInterface($typeClassOrPrototype, Type::class);
                $prototypes[] = $typeClassOrPrototype::prototype();
            }
        }

        return new self($prototypes, false);
    }

    /**
     * @return ProcessingTypes
     */
    public static function supportAll()
    {
        return new self([], true);
    }

    /**
     * @param array $definition
     * @return ProcessingTypes
     */
    public static function fromArray(array $definition)
    {
        if (isset($definition['support_all']) && $definition['support_all']) {
            return self::supportAll();
        }

        if (isset($definition['processing_types'])) {
            return self::support($definition['processing_types']);
        }

        return self::support($definition);
    }

    /**
     * @param array $processingTypes
     * @param bool $supportAll
     */
    private function __construct(array $processingTypes, $supportAll)
    {
        Assertion::allIsInstanceOf($processingTypes, Prototype::class);
        Assertion::boolean($supportAll);

        $this->supportedProcessingTypes = $processingTypes;
        $this->supportAllTypes = $supportAll;
    }

    /**
     * @param Prototype $prototype
     * @return bool
     */
    public function isSupported(Prototype $prototype)
    {
        if ($this->supportAllTypes) return true;

        foreach($this->supportedProcessingTypes as $processingType) {
            if ($processingType->of() === $prototype->of()) {
                return true;
            }
        }

        return false;
    }

    /**
     * @return bool
     */
    public function areAllTypesSupported()
    {
        return $this->supportAllTypes;
    }

    /**
     * @return array
     */
    public function typeList()
    {
        return array_map(function (Prototype $prototype) {return $prototype->of();}, $this->supportedProcessingTypes);
    }

    /**
     * @return array
     */
    public function toArray()
    {
        return [
            'support_all' => $this->supportAllTypes,
            'processing_types' => $this->typeList(),
        ];
    }
}