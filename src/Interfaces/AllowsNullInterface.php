<?php

declare(strict_types=1);

namespace Rcalicdan\ReflectionSerializer\Interfaces;

/**
 * Dictates nullability constraints.
 * This is critical for validation and serialization to determine if a `null` value
 * represents a valid, intended state or a strict type error.
 */
interface AllowsNullInterface
{
    /**
     * Clarifies if the absence of a value (null) satisfies the entity's type requirements.
     */
    public function allowsNull(): bool;
}