<?php

declare(strict_types=1);

namespace Rcalicdan\ReflectionSerializer\Data;

use Rcalicdan\ReflectionSerializer\Interfaces\ReflectionTypeInterface;
use Rcalicdan\ReflectionSerializer\Interfaces\ReflectionUnionTypeInterface;
use Rcalicdan\ReflectionSerializer\Internal\TypeResolver;

readonly class UnionTypeData implements ReflectionUnionTypeInterface
{
    public function __construct(
        /**
         * @var ReflectionTypeInterface[]
         */
        private array $types,
    ) {
    }

    public static function fromReflection(\ReflectionUnionType $type): self
    {
        return new self(
            types: array_map(
                fn (\ReflectionType $t) => TypeResolver::resolve($t),
                $type->getTypes(),
            ),
        );
    }

    /**
     * @return ReflectionTypeInterface[]
     */
    public function getTypes(): array
    {
        return $this->types;
    }

    public function allowsNull(): bool
    {
        foreach ($this->types as $type) {
            if ($type instanceof NamedTypeData && $type->getName() === 'null') {
                return true;
            }
        }

        return false;
    }

    public function __toString(): string
    {
        return implode('|', array_map(
            function (ReflectionTypeInterface $t) {
                if ($t instanceof IntersectionTypeData) {
                    return '(' . (string) $t . ')';
                }

                return (string) $t;
            },
            $this->types,
        ));
    }
}
