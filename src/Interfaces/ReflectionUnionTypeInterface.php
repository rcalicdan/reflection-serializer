<?php

declare(strict_types=1);

namespace Rcalicdan\ReflectionSerializer\Interfaces;

/**
 * Represents multiple acceptable data types (e.g., `int|string`).
 * Required for systems to validate polymorphic data where a single strict type constraint is insufficient.
 */
interface ReflectionUnionTypeInterface extends ReflectionTypeInterface
{
    /**
     * Breaks down the union into its individual acceptable type conditions for targeted evaluation.
     * 
     * @return ReflectionTypeInterface[]
     */
    public function getTypes(): array;
}