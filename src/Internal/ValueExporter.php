<?php

declare(strict_types=1);

namespace Rcalicdan\ReflectionSerializer\Internal;

/**
 * @internal
 */
final class ValueExporter
{
    public static function export(mixed $value): string
    {
        return match (true) {
            $value === null   => 'null',
            $value === true   => 'true',
            $value === false  => 'false',
            is_string($value) => '"' . addslashes($value) . '"',
            is_array($value)  => '[' . implode(', ', array_map(self::export(...), $value)) . ']',
            default           => (string) $value,
        };
    }
}