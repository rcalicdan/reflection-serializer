<?php

declare(strict_types=1);

namespace Rcalicdan\ReflectionSerializer\Interfaces;

/**
 * Enforces state immutability.
 * Hydrators need this to understand if they can mutate a property post-instantiation, 
 * or if the data must absolutely be provided upfront via the constructor.
 */
interface IsReadOnlyInterface
{
    /**
     * Confirms if the entity's state is permanently locked after its initial assignment.
     */
    public function isReadOnly(): bool;
}