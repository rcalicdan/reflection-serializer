<?php

declare(strict_types=1);

namespace Rcalicdan\ReflectionSerializer\Interfaces;

interface HasTypeInterface
{
    public function hasType(): bool;

    public function getType(): ?ReflectionTypeInterface;
}
