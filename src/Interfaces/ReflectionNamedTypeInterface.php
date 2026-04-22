<?php

declare(strict_types=1);

namespace Rcalicdan\ReflectionSerializer\Interfaces;

interface ReflectionNamedTypeInterface extends ReflectionTypeInterface, HasNameInterface
{
    public function isBuiltin(): bool;
}
