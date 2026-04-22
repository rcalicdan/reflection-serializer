<?php

declare(strict_types=1);

namespace Rcalicdan\ReflectionSerializer\Interfaces;

interface ReflectionTypeInterface extends AllowsNullInterface
{
    public function __toString(): string;
}
