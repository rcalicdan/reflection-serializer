<?php

declare(strict_types=1);

namespace Rcalicdan\ReflectionSerializer\Interfaces;

interface HasDefaultValueInterface
{
    public function getDefaultValue(): mixed;
}
