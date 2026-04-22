<?php

declare(strict_types=1);

namespace Rcalicdan\ReflectionSerializer\Interfaces;

interface ReflectionAttributeInterface
{
    public function getName(): string;
    public function getTarget(): int;
    public function getArguments(): array;
    public function newInstance(): object;
}