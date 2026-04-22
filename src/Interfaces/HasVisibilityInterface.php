<?php

declare(strict_types=1);

namespace Rcalicdan\ReflectionSerializer\Interfaces;

/**
 * Determines the access boundaries of a class member.
 * Serializers and hydrators rely on this to decide whether a member can be accessed natively
 * or if it requires reflection/closure workarounds to bypass scope restrictions.
 */
interface HasVisibilityInterface
{
    /**
     * Indicates if the member is freely accessible from any scope.
     */
    public function isPublic(): bool;

    /**
     * Indicates if the member's access is restricted strictly to the declaring class and its descendants.
     */
    public function isProtected(): bool;

    /**
     * Indicates if the member is encapsulated entirely within the declaring class.
     */
    public function isPrivate(): bool;
}