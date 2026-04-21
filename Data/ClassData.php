<?php

declare(strict_types=1);

namespace Rcalicdan\ReflectionSerializer\Data;

readonly class ClassData
{
    public function __construct(
        public string $name,
        public string $shortName,
        public string $namespace,
        public bool $isAbstract,
        public bool $isFinal,
        public bool $isReadonly,
        public bool $isInterface,
        public bool $isTrait,
        public bool $isEnum,
        public bool $isAnonymous,
        public ?string $parent,
        /** @var string[] */
        public array $interfaces,
        /** @var string[] */
        public array $traits,
        /** @var ConstantData[] */
        public array $constants,
        /** @var PropertyData[] */
        public array $properties,
        /** @var MethodData[] */
        public array $methods,
        /** @var AttributeData[] */
        public array $attributes,
    ) {}

    // --- Named factories ---

    public static function fromReflection(\ReflectionClass $class): self
    {
        return new self(
            name:        $class->getName(),
            shortName:   $class->getShortName(),
            namespace:   $class->getNamespaceName(),
            isAbstract:  $class->isAbstract(),
            isFinal:     $class->isFinal(),
            isReadonly:  $class->isReadOnly(),
            isInterface: $class->isInterface(),
            isTrait:     $class->isTrait(),
            isEnum:      $class->isEnum(),
            isAnonymous: $class->isAnonymous(),
            parent:      $class->getParentClass()
                            ? $class->getParentClass()->getName()
                            : null,
            interfaces:  array_keys($class->getInterfaces()),
            traits:      array_keys($class->getTraits()),
            constants:   self::resolveConstants($class),
            properties:  self::resolveProperties($class),
            methods:     self::resolveMethods($class),
            attributes:  self::resolveAttributes($class),
        );
    }

    // --- Helpers ---

    public function hasParent(): bool
    {
        return $this->parent !== null;
    }

    public function hasInterface(string $interface): bool
    {
        return in_array($interface, $this->interfaces, strict: true);
    }

    public function hasTrait(string $trait): bool
    {
        return in_array($trait, $this->traits, strict: true);
    }

    public function getMethod(string $name): ?MethodData
    {
        foreach ($this->methods as $method) {
            if ($method->name === $name) {
                return $method;
            }
        }

        return null;
    }

    public function getProperty(string $name): ?PropertyData
    {
        foreach ($this->properties as $property) {
            if ($property->name === $name) {
                return $property;
            }
        }

        return null;
    }

    public function getConstant(string $name): ?ConstantData
    {
        foreach ($this->constants as $constant) {
            if ($constant->name === $name) {
                return $constant;
            }
        }

        return null;
    }

    public function getConstructor(): ?MethodData
    {
        foreach ($this->methods as $method) {
            if ($method->isConstructor) {
                return $method;
            }
        }

        return null;
    }

    // --- Private helpers ---

    /**
     * @return ConstantData[]
     */
    private static function resolveConstants(\ReflectionClass $class): array
    {
        return array_values(array_map(
            fn(\ReflectionClassConstant $c) => ConstantData::fromReflection($c),
            $class->getReflectionConstants(),
        ));
    }

    /**
     * @return PropertyData[]
     */
    private static function resolveProperties(\ReflectionClass $class): array
    {
        return array_values(array_map(
            fn(\ReflectionProperty $p) => PropertyData::fromReflection($p),
            $class->getProperties(),
        ));
    }

    /**
     * @return MethodData[]
     */
    private static function resolveMethods(\ReflectionClass $class): array
    {
        return array_values(array_map(
            fn(\ReflectionMethod $m) => MethodData::fromReflection($m),
            $class->getMethods(),
        ));
    }

    /**
     * @return AttributeData[]
     */
    private static function resolveAttributes(\ReflectionClass $class): array
    {
        return array_map(
            fn(\ReflectionAttribute $a) => AttributeData::fromReflection($a),
            $class->getAttributes(),
        );
    }
}