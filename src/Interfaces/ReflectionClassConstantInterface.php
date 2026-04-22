<?php

declare(strict_types=1);

namespace Rcalicdan\ReflectionSerializer\Interfaces;

interface ReflectionClassConstantInterface
{
    public function getName(): string;
    public function getValue(): mixed;
    public function isPublic(): bool;
    public function isProtected(): bool;
    public function isPrivate(): bool;
    public function isFinal(): bool;

    /**
     * @return ReflectionAttributeInterface[]
     */
    public function getAttributes(): array;
}