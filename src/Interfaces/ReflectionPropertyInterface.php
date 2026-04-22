<?php

declare(strict_types=1);

namespace Rcalicdan\ReflectionSerializer\Interfaces;

/**
 * Represents the state-holding members of an object blueprint.
 * Provides object mappers and serializers the exact constraints needed to discover, 
 * read from, and safely write to an object's internal state.
 */
interface ReflectionPropertyInterface extends 
    HasNameInterface, 
    HasTypeInterface, 
    HasVisibilityInterface, 
    IsStaticInterface, 
    IsReadOnlyInterface, 
    IsPromotedInterface,
    HasDefaultValueInterface, 
    HasAttributesInterface
{
    /**
     * Validates if the property was assigned a predefined state at the class level,
     * allowing hydrators to safely fall back to `getDefaultValue()`.
     */
    public function hasDefaultValue(): bool;
}