<?php

declare(strict_types=1);

namespace Rcalicdan\ReflectionSerializer\Interfaces;

interface ReflectionParameterInterface
{
    public function getName(): string;
    public function getPosition(): int;
    public function hasType(): bool;
    public function getType(): ?ReflectionTypeInterface;
    public function allowsNull(): bool;
    public function isVariadic(): bool;
    public function isPassedByReference(): bool;
    public function isPromoted(): bool;
    public function isOptional(): bool;
    public function isDefaultValueAvailable(): bool;
    public function getDefaultValue(): mixed;

    /**
     * @return ReflectionAttributeInterface[]
     */
    public function getAttributes(): array;
}