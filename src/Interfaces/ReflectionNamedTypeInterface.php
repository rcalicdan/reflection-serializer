<?php

declare(strict_types=1);

namespace Rcalicdan\ReflectionSerializer\Interfaces;

/**
 * Represents a singular, explicit type declaration.
 * Allows the direct resolution of straightforward type dependencies like primitives or distinct classes.
 */
interface ReflectionNamedTypeInterface extends ReflectionTypeInterface, HasNameInterface
{
    /**
     * Differentiates between native PHP scalars (int, string) and userland objects/interfaces, 
     * which dictates whether the value should be directly assigned or deeply instantiated.
     */
    public function isBuiltin(): bool;
}