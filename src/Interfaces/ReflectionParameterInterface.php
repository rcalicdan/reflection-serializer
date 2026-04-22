<?php

declare(strict_types=1);

namespace Rcalicdan\ReflectionSerializer\Interfaces;

interface ReflectionParameterInterface extends
    HasNameInterface,
    HasTypeInterface,
    AllowsNullInterface,
    IsPromotedInterface,
    HasDefaultValueInterface,
    HasAttributesInterface
{
    public function getPosition(): int;

    public function isVariadic(): bool;

    public function isPassedByReference(): bool;

    public function isOptional(): bool;

    public function isDefaultValueAvailable(): bool;
}
