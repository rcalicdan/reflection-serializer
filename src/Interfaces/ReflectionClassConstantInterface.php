<?php

declare(strict_types=1);

namespace Rcalicdan\ReflectionSerializer\Interfaces;

/**
 * Represents immutable configuration attached to a class.
 * Useful for exposing predefined domain values or flags without requiring an object instance.
 */
interface ReflectionClassConstantInterface extends 
    HasNameInterface, 
    HasVisibilityInterface, 
    IsFinalInterface, 
    HasAttributesInterface
{
    /**
     * Extracts the hardcoded state of the constant to be used in serialization or logic branching.
     */
    public function getValue(): mixed;
}