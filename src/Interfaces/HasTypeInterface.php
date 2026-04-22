<?php

declare(strict_types=1);

namespace Rcalicdan\ReflectionSerializer\Interfaces;

/**
 * Provides type-safety constraints for state-holding or value-passing entities.
 * Consumers use this to understand if they must cast values, validate input, or map data
 * to a specific DTO or primitive during hydration.
 */
interface HasTypeInterface
{
    /**
     * Signals whether the developer explicitly restricted the data types allowed for this entity.
     */
    public function hasType(): bool;

    /**
     * Provides the specific type constraint rules, or null if the entity accepts mixed/untyped data.
     */
    public function getType(): ?ReflectionTypeInterface;
}