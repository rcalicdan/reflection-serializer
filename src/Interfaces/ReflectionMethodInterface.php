<?php

declare(strict_types=1);

namespace Rcalicdan\ReflectionSerializer\Interfaces;

interface ReflectionMethodInterface extends
    HasNameInterface,
    HasVisibilityInterface,
    IsStaticInterface,
    IsAbstractInterface,
    IsFinalInterface,
    HasAttributesInterface
{
    public function isConstructor(): bool;

    public function isDestructor(): bool;

    public function hasReturnType(): bool;

    public function getReturnType(): ?ReflectionTypeInterface;

    /**
     * @return ReflectionParameterInterface[]
     */
    public function getParameters(): array;
}
