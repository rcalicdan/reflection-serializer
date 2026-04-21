<?php

declare(strict_types=1);

namespace Rcalicdan\ReflectionSerializer\Data;

final readonly class ParameterData
{
    public function __construct(
        public string $name,
        public int $position,
        public ?TypeData $type,
        public bool $isNullable,
        public bool $isVariadic,
        public bool $isPassedByReference,
        public bool $isPromoted,
        public bool $hasDefault,
        public mixed $defaultValue,
        /** @var AttributeData[] */
        public array $attributes,
    ) {}

    public static function fromReflection(\ReflectionParameter $parameter): self
    {
        [$hasDefault, $defaultValue] = self::resolveDefault($parameter);

        return new self(
            name: $parameter->getName(),
            position: $parameter->getPosition(),
            type: self::resolveType($parameter),
            isNullable: $parameter->allowsNull(),
            isVariadic: $parameter->isVariadic(),
            isPassedByReference: $parameter->isPassedByReference(),
            isPromoted: $parameter->isPromoted(),
            hasDefault: $hasDefault,
            defaultValue: $defaultValue,
            attributes: self::resolveAttributes($parameter),
        );
    }

    public function hasType(): bool
    {
        return $this->type !== null;
    }

    public function isOptional(): bool
    {
        return $this->hasDefault || $this->isVariadic;
    }

    /**
     * Returns a human-readable string representation of the parameter,
     * e.g. "?string $name = null", "int ...$values", "Foo &$ref"
     */
    public function toString(): string
    {
        $parts = [];

        if ($this->type !== null) {
            $parts[] = $this->type->toString();
        }

        $name = ($this->isPassedByReference ? '&' : '')
            . ($this->isVariadic ? '...' : '')
            . '$' . $this->name;

        $parts[] = $name;

        if ($this->hasDefault) {
            $parts[] = '= ' . self::exportValue($this->defaultValue);
        }

        return implode(' ', $parts);
    }

    private static function resolveType(\ReflectionParameter $parameter): ?TypeData
    {
        $type = $parameter->getType();

        if ($type === null) {
            return null;
        }

        return match (true) {
            $type instanceof \ReflectionNamedType        => TypeData::fromNamed(
                $type->getName(),
                $type->allowsNull(),
            ),
            $type instanceof \ReflectionUnionType        => TypeData::fromUnion(
                array_map(
                    fn(\ReflectionNamedType $t) => $t->getName(),
                    $type->getTypes()
                ),
                $parameter->allowsNull(),
            ),
            $type instanceof \ReflectionIntersectionType => TypeData::fromIntersection(
                array_map(
                    fn(\ReflectionNamedType $t) => $t->getName(),
                    $type->getTypes()
                ),
            ),
            default => null,
        };
    }

    private static function resolveDefault(\ReflectionParameter $parameter): array
    {
        // Parameters with no default and variadic params have no default value
        if ($parameter->isVariadic() || !$parameter->isOptional()) {
            return [false, null];
        }

        try {
            return [true, $parameter->getDefaultValue()];
        } catch (\ReflectionException) {
            // Default is defined in a C extension (internal function),
            // not accessible from userland — we flag it but store null
            return [true, null];
        }
    }

    /**
     * @return AttributeData[]
     */
    private static function resolveAttributes(\ReflectionParameter $parameter): array
    {
        return array_map(
            fn(\ReflectionAttribute $a) => AttributeData::fromReflection($a),
            $parameter->getAttributes(),
        );
    }

    private static function exportValue(mixed $value): string
    {
        return match (true) {
            $value === null  => 'null',
            $value === true  => 'true',
            $value === false => 'false',
            is_string($value) => '"' . addslashes($value) . '"',
            is_array($value)  => '[' . implode(', ', array_map(self::exportValue(...), $value)) . ']',
            default           => (string) $value,
        };
    }
}
