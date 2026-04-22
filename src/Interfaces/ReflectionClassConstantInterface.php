<?php

declare(strict_types=1);

namespace Rcalicdan\ReflectionSerializer\Interfaces;

interface ReflectionClassConstantInterface extends
    HasNameInterface,
    HasVisibilityInterface,
    IsFinalInterface,
    HasAttributesInterface
{
    public function getValue(): mixed;
}
