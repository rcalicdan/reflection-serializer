<?php

declare(strict_types=1);

namespace Rcalicdan\ReflectionSerializer\Interfaces;

interface ReflectionTypeInterface
{
    public function allowsNull(): bool;
    public function __toString(): string;
}