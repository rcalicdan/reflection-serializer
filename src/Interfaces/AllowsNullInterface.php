<?php

declare(strict_types=1);

namespace Rcalicdan\ReflectionSerializer\Interfaces;

interface AllowsNullInterface
{
    public function allowsNull(): bool;
}
