<?php

declare(strict_types=1);

namespace Rcalicdan\ReflectionSerializer\Interfaces;

/**
 * Exposes PHP 8 attributes attached to the entity.
 * This allows serializers, validators, or routers to process domain-specific metadata
 * without needing to know the exact structural type of the entity.
 */
interface HasAttributesInterface
{
    /**
     * Retrieves the metadata configurations applied to this entity.
     * 
     * @return ReflectionAttributeInterface[]
     */
    public function getAttributes(): array;
}