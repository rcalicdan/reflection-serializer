<?php

declare(strict_types=1);

namespace Rcalicdan\ReflectionSerializer\Interfaces;

/**
 * Differentiates between instance-level and class-level scope.
 * This is vital for determining whether an actual object instance must be provided 
 * to interact with the member, or if the class reference alone is sufficient.
 */
interface IsStaticInterface
{
    /**
     * Confirms if the member belongs globally to the class blueprint rather than a specific object instance.
     */
    public function isStatic(): bool;
}