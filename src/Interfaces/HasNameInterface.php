<?php

declare(strict_types=1);

namespace Rcalicdan\ReflectionSerializer\Interfaces;

/**
 * Ensures the reflection entity can be uniquely identified by a string identifier.
 * This is crucial for index-building, searching, and mapping keys during serialization processes.
 */
interface HasNameInterface
{
    /**
     * Resolves the identifier used to reference this specific entity in the original codebase.
     */
    public function getName(): string;
}