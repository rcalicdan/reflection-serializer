<?php

declare(strict_types=1);

namespace Rcalicdan\ReflectionSerializer\Data;

use Rcalicdan\ReflectionSerializer\Interfaces\ReflectionNamedTypeInterface;
use Rcalicdan\ReflectionSerializer\Interfaces\ReflectionUnionTypeInterface;

readonly class UnionTypeData implements ReflectionUnionTypeInterface
{
    public function __construct(
        /** @var NamedTypeData[] */
        private array $types,
    ) {}

    /**
     * @return ReflectionNamedTypeInterface[]
     */
    public function getTypes(): array
    {
        return $this->types;
    }

    public function allowsNull(): bool
    {
        foreach ($this->types as $type) {
            if ($type->getName() === 'null') {
                return true;
            }
        }

        return false;
    }

    public function __toString(): string
    {
        return implode('|', array_map(
            fn(NamedTypeData $t) => $t->getName(),
            $this->types,
        ));
    }
}