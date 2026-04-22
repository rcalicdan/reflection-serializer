<?php

declare(strict_types=1);

namespace Rcalicdan\ReflectionSerializer\Interfaces;

/**
 * Indicates inheritance and modification restrictions.
 * Useful for proxy generators, mockers, or class extenders to know if they are 
 * strictly prohibited from overriding this entity.
 */
interface IsFinalInterface
{
    /**
     * Verifies if the developer explicitly locked this entity from further extension or overriding.
     */
    public function isFinal(): bool;
}