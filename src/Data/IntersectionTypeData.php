<?php

declare(strict_types=1);

namespace Rcalicdan\ReflectionSerializer\Data;

use Rcalicdan\ReflectionSerializer\Interfaces\ReflectionTypeInterface;
use Rcalicdan\ReflectionSerializer\Internal\TypeResolver;

readonly class IntersectionTypeData implements ReflectionTypeInterface
{
    public function __construct(
        /**
         * @var ReflectionTypeInterface[]
         */
        private array $types,
    ) {
    }

    public static function fromReflection(\ReflectionIntersectionType $type): self
    {
        return new self(
            types: array_map(
                fn (\ReflectionType $t) => TypeResolver::resolve($t),
                $type->getTypes(),
            ),
        );
    }

    /**
     * @return ReflectionTypeInterface[]
     */
    public function getTypes(): array
    {
        return $this->types;
    }

    public function allowsNull(): bool
    {
        return false;
    }

    public function __toString(): string
    {
        return implode('&', array_map(
            fn (ReflectionTypeInterface $t) => (string) $t,
            $this->types,
        ));
    }
}
