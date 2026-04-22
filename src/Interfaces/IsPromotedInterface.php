<?php

declare(strict_types=1);

namespace Rcalicdan\ReflectionSerializer\Interfaces;

/**
 * Bridges the gap between constructor parameters and class properties.
 * Knowing this helps prevent duplicate processing when a system analyzes both 
 * the object's instantiation signature and its internal state map.
 */
interface IsPromotedInterface
{
    /**
     * Indicates if this entity acts dually as both a constructor input and an internal property.
     */
    public function isPromoted(): bool;
}