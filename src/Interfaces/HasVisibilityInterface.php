<?php

declare(strict_types=1);

namespace Rcalicdan\ReflectionSerializer\Interfaces;

interface HasVisibilityInterface
{
    public function isPublic(): bool;

    public function isProtected(): bool;

    public function isPrivate(): bool;
}
