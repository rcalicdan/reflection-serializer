<?php

declare(strict_types=1);

namespace Rcalicdan\ReflectionSerializer\Internal;

use Rcalicdan\ReflectionSerializer\Data\TypeData;

/**
 * @internal
 */
final class TypeResolver
{
    public static function resolve(
        \ReflectionNamedType|\ReflectionUnionType|\ReflectionIntersectionType|null $type,
        bool $allowsNull = false,
    ): ?TypeData {
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
                $allowsNull,
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
}
