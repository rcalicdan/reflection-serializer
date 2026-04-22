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
        if (\is_array($value)) {
            return self::exportArray($value);
        }

        return match (true) {
            $value === null   => 'null',
            $value === true   => 'true',
            $value === false  => 'false',
            is_string($value) => '"' . addslashes($value) . '"',
            default           => (string) $value,
        };
    }

    private static function exportArray(array $value): string
    {
        if ($value === []) {
            return '[]';
        }

        $isList = array_is_list($value);
        $parts = [];

        foreach ($value as $key => $item) {
            $exportedValue = self::export($item);

            if ($isList) {
                $parts[] = $exportedValue;
            } else {
                $exportedKey = \is_string($key) ? '"' . addslashes((string)$key) . '"' : $key;
                $parts[] = $exportedKey . ' => ' . $exportedValue;
            }
        }

        return '[' . implode(', ', $parts) . ']';
    }
}
