<?php

declare(strict_types=1);

namespace Rcalicdan\ReflectionSerializer\Interfaces;

interface ReflectionPropertyInterface
{
    public function getName(): string;
    public function hasType(): bool;
    public function getType(): ?ReflectionTypeInterface;
    public function isPublic(): bool;
    public function isProtected(): bool;
    public function isPrivate(): bool;
    public function isStatic(): bool;
    public function isReadOnly(): bool;
    public function isPromoted(): bool;
    public function hasDefaultValue(): bool;
    public function getDefaultValue(): mixed;

    /**
     * @return ReflectionAttributeInterface[]
     */
    public function getAttributes(): array;
}