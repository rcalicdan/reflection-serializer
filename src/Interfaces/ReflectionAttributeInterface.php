<?php

declare(strict_types=1);

namespace Rcalicdan\ReflectionSerializer\Interfaces;

/**
 * Represents the detached metadata from a PHP attribute.
 * This enables the system to evaluate routing, validation, or serialization rules 
 * attached directly to the code structure without triggering side effects.
 */
interface ReflectionAttributeInterface extends HasNameInterface
{
    /**
     * Reveals the structural elements (classes, methods, etc.) this attribute is permitted to be attached to.
     */
    public function getTarget(): int;

    /**
     * Exposes the raw configuration data passed into the attribute before instantiation.
     */
    public function getArguments(): array;

    /**
     * Hydrates the attribute into a usable object so its internal logic and methods can be executed.
     */
    public function newInstance(): object;
}