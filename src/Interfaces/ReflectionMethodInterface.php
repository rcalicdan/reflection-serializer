<?php

declare(strict_types=1);

namespace Rcalicdan\ReflectionSerializer\Interfaces;

/**
 * Represents an executable behavior within a class.
 * Allows the system to understand input requirements and output guarantees 
 * to ensure safe and predictable invocations.
 */
interface ReflectionMethodInterface extends 
    HasNameInterface, 
    HasVisibilityInterface, 
    IsStaticInterface, 
    IsAbstractInterface, 
    IsFinalInterface, 
    HasAttributesInterface
{
    /**
     * Identifies if this method is responsible for bootstrapping the object's initial state.
     */
    public function isConstructor(): bool;

    /**
     * Identifies if this method dictates the object's teardown sequence.
     */
    public function isDestructor(): bool;

    /**
     * Clarifies if the execution of this method is guaranteed to yield a predictable type constraint.
     */
    public function hasReturnType(): bool;

    /**
     * Details the expected output type, allowing consumers to format or cast the resulting value.
     */
    public function getReturnType(): ?ReflectionTypeInterface;

    /**
     * Exposes the specific inputs required to execute this behavior successfully.
     * 
     * @return ReflectionParameterInterface[]
     */
    public function getParameters(): array;
}