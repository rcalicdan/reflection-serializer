<?php

declare(strict_types=1);

namespace Rcalicdan\ReflectionSerializer\Interfaces;

/**
 * Acts as the central blueprint of a class, trait, or interface.
 * Aggregates all structural and behavioral members, providing the necessary map 
 * for instantiating objects or converting them into serialized payloads.
 */
interface ReflectionClassInterface extends 
    HasNameInterface, 
    IsAbstractInterface, 
    IsFinalInterface, 
    IsReadOnlyInterface, 
    HasAttributesInterface
{
    /**
     * Provides the class identifier without its namespace, useful for logging or simplified mapping.
     */
    public function getShortName(): string;

    /**
     * Isolates the organizational boundary of the class to aid in autoloading or namespace-based grouping.
     */
    public function getNamespaceName(): string;

    /**
     * Indicates if this blueprint represents a strict contract rather than an instantiable object.
     */
    public function isInterface(): bool;

    /**
     * Indicates if this blueprint represents reusable horizontal code intended to be mixed into other classes.
     */
    public function isTrait(): bool;

    /**
     * Indicates if this blueprint represents a strict set of predefined, finite instances.
     */
    public function isEnum(): bool;

    /**
     * Reveals if the class was declared dynamically at runtime without an explicit name.
     */
    public function isAnonymous(): bool;

    /**
     * Points to the inherited parent blueprint, essential for crawling inheritance chains during deep serialization.
     */
    public function getParentClass(): string|false;

    /**
     * Lists the contracts this class guarantees to fulfill.
     * 
     * @return string[]
     */
    public function getInterfaceNames(): array;

    /**
     * Lists the mixins integrated into this class's structure.
     * 
     * @return string[]
     */
    public function getTraitNames(): array;

    /**
     * Verifies compliance with a specific contract, useful for polymorphism checks before processing.
     */
    public function implementsInterface(string $interface): bool;

    /**
     * Verifies the inclusion of a specific mixin, useful for feature detection.
     */
    public function usesTrait(string $trait): bool;

    /**
     * Checks for the existence of an executable behavior before attempting invocation.
     */
    public function hasMethod(string $name): bool;

    /**
     * Checks for the existence of a state-holding member before attempting extraction or hydration.
     */
    public function hasProperty(string $name): bool;

    /**
     * Checks for the existence of immutable configuration data.
     */
    public function hasConstant(string $name): bool;

    /**
     * Retrieves the initialization method, strictly required by hydrators to resolve required dependencies for instantiation.
     */
    public function getConstructor(): ?ReflectionMethodInterface;

    /**
     * Isolates a specific executable behavior for detailed analysis or invocation.
     */
    public function getMethod(string $name): ReflectionMethodInterface;

    /**
     * Isolates a specific state-holding member to read its value or inject data.
     */
    public function getProperty(string $name): ReflectionPropertyInterface;

    /**
     * Provides the complete set of executable behaviors for structural analysis.
     * 
     * @return ReflectionMethodInterface[]
     */
    public function getMethods(): array;

    /**
     * Provides the complete mapping of object state, required for converting an object to a serialized array.
     * 
     * @return ReflectionPropertyInterface[]
     */
    public function getProperties(): array;

    /**
     * Provides all immutable configurations bound to this class.
     * 
     * @return ReflectionClassConstantInterface[]
     */
    public function getReflectionConstants(): array;
}