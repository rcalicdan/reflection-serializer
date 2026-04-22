<?php

declare(strict_types=1);

namespace Rcalicdan\ReflectionSerializer\Data;

use Rcalicdan\ReflectionSerializer\Interfaces\ReflectionNamedTypeInterface;

readonly class NamedTypeData implements ReflectionNamedTypeInterface
{
    private const array BUILTINS =[
        'int', 'float', 'string', 'bool', 'array',
        'object', 'null', 'void', 'never', 'mixed',
        'callable', 'iterable', 'self', 'static', 'parent',
    ];

    public function __construct(
        private string $name,
        private bool $nullable,
    ) {}

    public static function fromReflection(\ReflectionNamedType $type): self
    {
        return new self(
            name: $type->getName(),
            nullable: $type->allowsNull(),
        );
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function allowsNull(): bool
    {
        return $this->nullable;
    }

    public function isBuiltin(): bool
    {
        return \in_array($this->name, self::BUILTINS, strict: true);
    }

    public function __toString(): string
    {
        return ($this->nullable ? '?' : '') . $this->name;
    }
}