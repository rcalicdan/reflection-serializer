<?php

declare(strict_types=1);

namespace Rcalicdan\ReflectionSerializer\Interfaces;

/**
 * Exposes a fallback state for properties or parameters.
 * When hydrating objects or calling methods with missing payload data, this ensures 
 * the system can gracefully fall back to the developer's intended initial state.
 */
interface HasDefaultValueInterface
{
    /**
     * Retrieves the natively assigned fallback value to use when no explicit data is provided.
     */
    public function getDefaultValue(): mixed;
}