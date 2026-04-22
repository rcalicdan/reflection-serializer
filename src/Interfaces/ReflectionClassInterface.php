<?php

declare(strict_types=1);

namespace Rcalicdan\ReflectionSerializer\Interfaces;

interface ReflectionClassInterface extends
    HasNameInterface,
    IsAbstractInterface,
    IsFinalInterface,
    IsReadOnlyInterface,
    HasAttributesInterface
{
    public function getShortName(): string;

    public function getNamespaceName(): string;

    public function isInterface(): bool;

    public function isTrait(): bool;

    public function isEnum(): bool;

    public function isAnonymous(): bool;

    public function getParentClass(): string|false;

    /**
     * @return string[]
     */
    public function getInterfaceNames(): array;

    /**
     * @return string[]
     */
    public function getTraitNames(): array;

    public function implementsInterface(string $interface): bool;

    public function usesTrait(string $trait): bool;

    public function hasMethod(string $name): bool;

    public function hasProperty(string $name): bool;

    public function hasConstant(string $name): bool;

    public function getConstructor(): ?ReflectionMethodInterface;

    public function getMethod(string $name): ReflectionMethodInterface;

    public function getProperty(string $name): ReflectionPropertyInterface;

    /**
     * @return ReflectionMethodInterface[]
     */
    public function getMethods(): array;

    /**
     * @return ReflectionPropertyInterface[]
     */
    public function getProperties(): array;

    /**
     * @return ReflectionClassConstantInterface[]
     */
    public function getReflectionConstants(): array;
}
