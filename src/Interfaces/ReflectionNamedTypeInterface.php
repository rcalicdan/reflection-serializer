<?php

declare(strict_types=1);

namespace Rcalicdan\ReflectionSerializer\Interfaces;

interface ReflectionNamedTypeInterface extends ReflectionTypeInterface
{
    public function getName(): string;

    public function isBuiltin(): bool;
}
