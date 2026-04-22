<?php

declare(strict_types=1);

namespace Rcalicdan\ReflectionSerializer\Interfaces;

/**
 * The base contract for representing PHP's type system declarations.
 * Ensures serializers and hydrators can validate incoming data formats 
 * against strict structural rules regardless of the type's complexity.
 */
interface ReflectionTypeInterface extends AllowsNullInterface
{
    /**
     * Converts the type rules into a readable PHP syntax string for logging, caching, or code generation.
     */
    public function __toString(): string;
}