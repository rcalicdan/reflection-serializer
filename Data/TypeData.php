<?php

declare(strict_types=1);

namespace Rcalicdan\ReflectionSerializer\Data;

final readonly class TypeData
{
    public function __construct(
        public string $kind,        
        public array $types,      
        public bool $isNullable,
    ) {}


    public static function fromNamed(string $type, bool $isNullable): self
    {
        return new self(
            kind: 'named',
            types: [$type],
            isNullable: $isNullable,
        );
    }

    public static function fromUnion(array $types, bool $isNullable): self
    {
        return new self(
            kind: 'union',
            types: $types,
            isNullable: $isNullable,
        );
    }

    public static function fromIntersection(array $types): self
    {
        return new self(
            kind: 'intersection',
            types: $types,
            isNullable: false,
        );
    }

    public function isNamed(): bool
    {
        return $this->kind === 'named';
    }

    public function isUnion(): bool
    {
        return $this->kind === 'union';
    }

    public function isIntersection(): bool
    {
        return $this->kind === 'intersection';
    }

    /**
     * Returns a human-readable type string, e.g. "int|string", "Foo&Bar", "?int"
     */
    public function toString(): string
    {
        return match ($this->kind) {
            'named'        => ($this->isNullable ? '?' : '') . $this->types[0],
            'union'        => implode('|', $this->types),
            'intersection' => implode('&', $this->types),
        };
    }
}