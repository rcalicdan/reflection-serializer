<?php

declare(strict_types=1);

namespace Rcalicdan\ReflectionSerializer\Internal;

use Rcalicdan\ReflectionSerializer\Interfaces\ReflectionTypeInterface;
use Rcalicdan\ReflectionSerializer\Data\IntersectionTypeData;
use Rcalicdan\ReflectionSerializer\Data\NamedTypeData;
use Rcalicdan\ReflectionSerializer\Data\UnionTypeData;

/**
 * @internal
 */
final class TypeResolver
{
    public static function resolve(
        \ReflectionNamedType|\ReflectionUnionType|\ReflectionIntersectionType|null $type,
    ): ?ReflectionTypeInterface {
        if ($type === null) {
            return null;
        }

        return match (true) {
            $type instanceof \ReflectionNamedType        => NamedTypeData::fromReflection($type),
            $type instanceof \ReflectionUnionType        => UnionTypeData::fromReflection($type),
            $type instanceof \ReflectionIntersectionType => IntersectionTypeData::fromReflection($type),
        };
    }

    /**
     * Reconstruct a type DTO from a plain array produced by ReflectionSerializer::toArray().
     */
    public static function fromArray(array $data): ReflectionTypeInterface
    {
        return match ($data['kind']) {
            'named' => new NamedTypeData(
                name:     $data['name'],
                nullable: $data['nullable'],
            ),
            'union' => new UnionTypeData(
                types: array_map(
                    fn(array $t) => self::fromArray($t),
                    $data['types'],
                ),
            ),
            'intersection' => new IntersectionTypeData(
                types: array_map(
                    fn(array $t) => self::fromArray($t),
                    $data['types'],
                ),
            ),
            default => throw new \InvalidArgumentException(
                "Unknown type kind: {$data['kind']}"
            ),
        };
    }
}