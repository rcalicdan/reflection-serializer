<?php

declare(strict_types=1);

namespace Rcalicdan\ReflectionSerializer\Interfaces;

interface ReflectionMethodInterface
{
    public function getName(): string;
    public function isPublic(): bool;
    public function isProtected(): bool;
    public function isPrivate(): bool;
    public function isStatic(): bool;
    public function isAbstract(): bool;
    public function isFinal(): bool;
    public function isConstructor(): bool;
    public function isDestructor(): bool;
    public function hasReturnType(): bool;
    public function getReturnType(): ?ReflectionTypeInterface;

    /**
     * @return ReflectionParameterInterface[]
     */
    public function getParameters(): array;

    /**
     * @return ReflectionAttributeInterface[]
     */
    public function getAttributes(): array;
}