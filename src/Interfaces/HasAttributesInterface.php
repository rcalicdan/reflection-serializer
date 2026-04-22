<?php

declare(strict_types=1);

namespace Rcalicdan\ReflectionSerializer\Interfaces;

interface HasAttributesInterface
{
    /**
     * @return ReflectionAttributeInterface[]
     */
    public function getAttributes(): array;
}
