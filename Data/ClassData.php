<?php

declare(strict_types=1);

namespace Rcalicdan\ReflectionSerializer\Data;

use Rcalicdan\ReflectionSerializer\Interfaces\ReflectionClassInterface;
use Rcalicdan\ReflectionSerializer\Interfaces\ReflectionMethodInterface;
use Rcalicdan\ReflectionSerializer\Interfaces\ReflectionPropertyInterface;
use Rcalicdan\ReflectionSerializer\Interfaces\ReflectionClassConstantInterface;
use Rcalicdan\ReflectionSerializer\Interfaces\ReflectionAttributeInterface;
use Rcalicdan\ReflectionSerializer\Exception\MethodNotFoundException;
use Rcalicdan\ReflectionSerializer\Exception\PropertyNotFoundException;

readonly class ClassData implements ReflectionClassInterface
{
    public function __construct(
        private string $name,
        private string $shortName,
        private string $namespace,
        private bool $abstract,
        private bool $final,
        private bool $readonly,
        private bool $interface,
        private bool $trait,
        private bool $enum,
        private bool $anonymous,
        private ?string $parent,
        /** @var string[] */
        private array $interfaces,
        /** @var string[] */
        private array $traits,
        /** @var ConstantData[] */
        private array $constants,
        /** @var PropertyData[] */
        private array $properties,
        /** @var MethodData[] */
        private array $methods,
        /** @var AttributeData[] */
        private array $attributes,
    ) {}

    // --- Named factories ---

    public static function fromReflection(\ReflectionClass $class): self
    {
        return new self(
            name: $class->getName(),
            shortName: $class->getShortName(),
            namespace: $class->getNamespaceName(),
            abstract: $class->isAbstract(),
            final: $class->isFinal(),
            readonly: $class->isReadOnly(),
            interface: $class->isInterface(),
            trait: $class->isTrait(),
            enum: $class->isEnum(),
            anonymous: $class->isAnonymous(),
            parent: $class->getParentClass()
                ? $class->getParentClass()->getName()
                : null,
            interfaces: array_keys($class->getInterfaces()),
            traits: array_keys($class->getTraits()),
            constants: array_values(array_map(
                fn(\ReflectionClassConstant $c) => ConstantData::fromReflection($c),
                $class->getReflectionConstants(),
            )),
            properties: array_values(array_map(
                fn(\ReflectionProperty $p) => PropertyData::fromReflection($p),
                $class->getProperties(),
            )),
            methods: array_values(array_map(
                fn(\ReflectionMethod $m) => MethodData::fromReflection($m),
                $class->getMethods(),
            )),
            attributes: array_map(
                fn(\ReflectionAttribute $a) => AttributeData::fromReflection($a),
                $class->getAttributes(),
            ),
        );
    }

    // --- ReflectionClassInterface ---

    public function getName(): string
    {
        return $this->name;
    }

    public function getShortName(): string
    {
        return $this->shortName;
    }

    public function getNamespaceName(): string
    {
        return $this->namespace;
    }

    public function isAbstract(): bool
    {
        return $this->abstract;
    }

    public function isFinal(): bool
    {
        return $this->final;
    }

    public function isReadOnly(): bool
    {
        return $this->readonly;
    }

    public function isInterface(): bool
    {
        return $this->interface;
    }

    public function isTrait(): bool
    {
        return $this->trait;
    }

    public function isEnum(): bool
    {
        return $this->enum;
    }

    public function isAnonymous(): bool
    {
        return $this->anonymous;
    }

    public function getParentClass(): string|false
    {
        return $this->parent ?? false;
    }

    /**
     * @return string[]
     */
    public function getInterfaceNames(): array
    {
        return $this->interfaces;
    }

    /**
     * @return string[]
     */
    public function getTraitNames(): array
    {
        return $this->traits;
    }

    public function implementsInterface(string $interface): bool
    {
        return in_array($interface, $this->interfaces, strict: true);
    }

    public function usesTrait(string $trait): bool
    {
        return in_array($trait, $this->traits, strict: true);
    }

    public function hasMethod(string $name): bool
    {
        foreach ($this->methods as $method) {
            if ($method->getName() === $name) {
                return true;
            }
        }

        return false;
    }

    public function hasProperty(string $name): bool
    {
        foreach ($this->properties as $property) {
            if ($property->getName() === $name) {
                return true;
            }
        }

        return false;
    }

    public function hasConstant(string $name): bool
    {
        foreach ($this->constants as $constant) {
            if ($constant->getName() === $name) {
                return true;
            }
        }

        return false;
    }

    public function getConstructor(): ?ReflectionMethodInterface
    {
        foreach ($this->methods as $method) {
            if ($method->isConstructor()) {
                return $method;
            }
        }

        return null;
    }

    public function getMethod(string $name): ReflectionMethodInterface
    {
        foreach ($this->methods as $method) {
            if ($method->getName() === $name) {
                return $method;
            }
        }

        throw new MethodNotFoundException($this->name, $name);
    }

    public function getProperty(string $name): ReflectionPropertyInterface
    {
        foreach ($this->properties as $property) {
            if ($property->getName() === $name) {
                return $property;
            }
        }

        throw new PropertyNotFoundException($this->name, $name);
    }

    /**
     * @return ReflectionMethodInterface[]
     */
    public function getMethods(): array
    {
        return $this->methods;
    }

    /**
     * @return ReflectionPropertyInterface[]
     */
    public function getProperties(): array
    {
        return $this->properties;
    }

    /**
     * @return ReflectionClassConstantInterface[]
     */
    public function getReflectionConstants(): array
    {
        return $this->constants;
    }

    /**
     * @return ReflectionAttributeInterface[]
     */
    public function getAttributes(): array
    {
        return $this->attributes;
    }
}
