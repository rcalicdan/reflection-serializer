<?php

declare(strict_types=1);

namespace Rcalicdan\ReflectionSerializer\Data;

readonly class PropertyData
{
    public function __construct(
        public string $name,
        public ?TypeData $type,
        public bool $isPublic,
        public bool $isProtected,
        public bool $isPrivate,
        public bool $isStatic,
        public bool $isReadonly,
        public bool $isPromoted,
        public bool $hasDefault,
        public mixed $defaultValue,
        /** @var AttributeData[] */
        public array $attributes,
    ) {}

    public static function fromReflection(\ReflectionProperty $property): self
    {
        [$hasDefault, $defaultValue] = self::resolveDefault($property);

        return new self(
            name: $property->getName(),
            type: self::resolveType($property),
            isPublic: $property->isPublic(),
            isProtected: $property->isProtected(),
            isPrivate: $property->isPrivate(),
            isStatic: $property->isStatic(),
            isReadonly: $property->isReadOnly(),
            isPromoted: $property->isPromoted(),
            hasDefault: $hasDefault,
            defaultValue: $defaultValue,
            attributes: self::resolveAttributes($property),
        );
    }

    public function hasType(): bool
    {
        return $this->type !== null;
    }

    public function visibility(): string
    {
        return match (true) {
            $this->isPublic    => 'public',
            $this->isProtected => 'protected',
            $this->isPrivate   => 'private',
        };
    }

    /**
     * Returns a human-readable string representation of the property,
     * e.g. "public readonly string $name", "protected static ?int $count = 0"
     */
    public function toString(): string
    {
        $parts = [$this->visibility()];

        if ($this->isStatic) {
            $parts[] = 'static';
        }

        if ($this->isReadonly) {
            $parts[] = 'readonly';
        }

        if ($this->type !== null) {
            $parts[] = $this->type->toString();
        }

        $parts[] = '$' . $this->name;

        if ($this->hasDefault && !$this->isPromoted) {
            $parts[] = '= ' . self::exportValue($this->defaultValue);
        }

        return implode(' ', $parts);
    }

    private static function resolveType(\ReflectionProperty $property): ?TypeData
    {
        $type = $property->getType();

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
                    $type->getTypes(),
                ),
                $type->allowsNull() ?? false,
            ),
            $type instanceof \ReflectionIntersectionType => TypeData::fromIntersection(
                array_map(
                    fn(\ReflectionNamedType $t) => $t->getName(),
                    $type->getTypes(),
                ),
            ),
            default => null,
        };
    }

    private static function resolveDefault(\ReflectionProperty $property): array
    {
        // Promoted properties delegate their default to the parameter
        // so we skip them here to avoid duplicating the value
        if ($property->isPromoted()) {
            return [false, null];
        }

        if (!$property->hasDefaultValue()) {
            return [false, null];
        }

        return [true, $property->getDefaultValue()];
    }

    /**
     * @return AttributeData[]
     */
    private static function resolveAttributes(\ReflectionProperty $property): array
    {
        return array_map(
            fn(\ReflectionAttribute $a) => AttributeData::fromReflection($a),
            $property->getAttributes(),
        );
    }

    private static function exportValue(mixed $value): string
    {
        return match (true) {
            $value === null   => 'null',
            $value === true   => 'true',
            $value === false  => 'false',
            \is_string($value) => '"' . addslashes($value) . '"',
            \is_array($value)  => '[' . implode(', ', array_map(self::exportValue(...), $value)) . ']',
            default           => (string) $value,
        };
    }
}
