<?php

declare(strict_types=1);

namespace Rcalicdan\ReflectionSerializer\Data;

use Rcalicdan\ReflectionSerializer\Interfaces\ReflectionParameterInterface;
use Rcalicdan\ReflectionSerializer\Interfaces\ReflectionTypeInterface;
use Rcalicdan\ReflectionSerializer\Internal\TypeResolver;
use Rcalicdan\ReflectionSerializer\Internal\ValueExporter;

readonly class ParameterData implements ReflectionParameterInterface
{
    public function __construct(
        private string $name,
        private int $position,
        private ?ReflectionTypeInterface $type,
        private bool $nullable,
        private bool $variadic,
        private bool $passByReference,
        private bool $promoted,
        private bool $hasDefault,
        private mixed $defaultValue,
        /**
         * @var AttributeData[]
         */
        private array $attributes,
    ) {
    }

    public static function fromReflection(\ReflectionParameter $parameter): self
    {
        [$hasDefault, $defaultValue] = self::resolveDefault($parameter);

        return new self(
            name:           $parameter->getName(),
            position:       $parameter->getPosition(),
            type:           TypeResolver::resolve(
                $parameter->getType(),
                $parameter->allowsNull(),
            ),
            nullable:       $parameter->allowsNull(),
            variadic:       $parameter->isVariadic(),
            passByReference: $parameter->isPassedByReference(),
            promoted:       $parameter->isPromoted(),
            hasDefault:     $hasDefault,
            defaultValue:   $defaultValue,
            attributes:     array_map(
                fn (\ReflectionAttribute $a) => AttributeData::fromReflection($a),
                $parameter->getAttributes(),
            ),
        );
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getPosition(): int
    {
        return $this->position;
    }

    public function hasType(): bool
    {
        return $this->type !== null;
    }

    public function getType(): ?ReflectionTypeInterface
    {
        return $this->type;
    }

    public function allowsNull(): bool
    {
        return $this->nullable;
    }

    public function isVariadic(): bool
    {
        return $this->variadic;
    }

    public function isPassedByReference(): bool
    {
        return $this->passByReference;
    }

    public function isPromoted(): bool
    {
        return $this->promoted;
    }

    public function isOptional(): bool
    {
        return $this->hasDefault || $this->variadic;
    }

    public function isDefaultValueAvailable(): bool
    {
        return $this->hasDefault;
    }

    public function getDefaultValue(): mixed
    {
        return $this->defaultValue;
    }

    /**
     * @return AttributeData[]
     */
    public function getAttributes(): array
    {
        return $this->attributes;
    }

    public function toString(): string
    {
        $parts = [];

        if ($this->type !== null) {
            $parts[] = (string) $this->type;
        }

        $name = ($this->passByReference ? '&' : '')
              . ($this->variadic ? '...' : '')
              . '$' . $this->name;

        $parts[] = $name;

        if ($this->hasDefault) {
            $parts[] = '= ' . ValueExporter::export($this->defaultValue);
        }

        return implode(' ', $parts);
    }

    private static function resolveDefault(\ReflectionParameter $parameter): array
    {
        if ($parameter->isVariadic() || ! $parameter->isOptional()) {
            return [false, null];
        }

        try {
            return [true, $parameter->getDefaultValue()];
        } catch (\ReflectionException) {
            return [true, null];
        }
    }
}
