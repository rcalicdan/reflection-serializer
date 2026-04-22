<?php

declare(strict_types=1);

namespace Rcalicdan\ReflectionSerializer\Interfaces;

interface ReflectionPropertyInterface extends
    HasNameInterface,
    HasTypeInterface,
    HasVisibilityInterface,
    IsStaticInterface,
    IsReadOnlyInterface,
    IsPromotedInterface,
    HasDefaultValueInterface,
    HasAttributesInterface
{
    public function hasDefaultValue(): bool;
}
