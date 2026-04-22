<?php

declare(strict_types=1);

namespace Rcalicdan\ReflectionSerializer\Interfaces;

interface ReflectionUnionTypeInterface extends ReflectionTypeInterface
{
    /**
     * @return ReflectionNamedTypeInterface[]
     */
    public function getTypes(): array;
}
