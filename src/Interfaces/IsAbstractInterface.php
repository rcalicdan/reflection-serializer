<?php

declare(strict_types=1);

namespace Rcalicdan\ReflectionSerializer\Interfaces;

/**
 * Signals an incomplete implementation.
 * Dependency Injection containers and instantiators use this to know they cannot 
 * instantiate or execute this entity directly and must resolve a concrete implementation instead.
 */
interface IsAbstractInterface
{
    /**
     * Determines if this entity serves only as a contract requiring concrete implementation elsewhere.
     */
    public function isAbstract(): bool;
}