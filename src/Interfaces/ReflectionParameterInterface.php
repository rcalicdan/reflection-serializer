<?php

declare(strict_types=1);

namespace Rcalicdan\ReflectionSerializer\Interfaces;

/**
 * Defines the specific requirements for providing data to an executable behavior.
 * Crucial for dependency injection containers and argument resolvers to satisfy 
 * the method's dependencies correctly.
 */
interface ReflectionParameterInterface extends 
    HasNameInterface, 
    HasTypeInterface, 
    AllowsNullInterface, 
    IsPromotedInterface, 
    HasDefaultValueInterface, 
    HasAttributesInterface
{
    /**
     * Determines the argument's execution order, critical when mapping positional arrays to method invocations.
     */
    public function getPosition(): int;

    /**
     * Signals if this parameter consumes an infinite number of trailing arguments into an array.
     */
    public function isVariadic(): bool;

    /**
     * Indicates if modifying this variable will alter the original memory reference passed by the caller.
     */
    public function isPassedByReference(): bool;

    /**
     * Clarifies if this dependency can be safely omitted by the caller during invocation.
     */
    public function isOptional(): bool;

    /**
     * Validates if the system can safely extract a fallback state using `getDefaultValue()`.
     */
    public function isDefaultValueAvailable(): bool;
}