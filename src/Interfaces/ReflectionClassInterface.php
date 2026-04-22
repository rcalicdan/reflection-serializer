<?php

declare(strict_types=1);

namespace Rcalicdan\ReflectionSerializer\Interfaces;

interface ReflectionClassInterface
{
    public function getName(): string;
    public function getShortName(): string;
    public function getNamespaceName(): string;
    public function isAbstract(): bool;
    public function isFinal(): bool;
    public function isReadOnly(): bool;
    public function isInterface(): bool;
    public function isTrait(): bool;
    public function isEnum(): bool;
    public function isAnonymous(): bool;

    /**
     * Returns the FQCN of the parent class or false if none.
     * Note: Deviates from PHP's ReflectionClass::getParentClass()
     * which returns ReflectionClass|false. Since we only store
     * the parent FQCN and not its full reflection data, we
     * return string|false instead.
     */
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

    /**
     * @return ReflectionAttributeInterface[]
     */
    public function getAttributes(): array;
}