<?php

declare(strict_types=1);

namespace Rcalicdan\ReflectionSerializer\Interfaces;

interface ReflectionUnionTypeInterface extends ReflectionTypeInterface
{
    /**
     * @return ReflectionTypeInterface[]
     */
    public function getTypes(): array;
}
