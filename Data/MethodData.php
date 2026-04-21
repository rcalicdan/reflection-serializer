<?php

declare(strict_types=1);

namespace Rcalicdan\ReflectionSerializer\Data;

readonly class MethodData
{
    public function __construct(
        public string $name,
        public bool $isPublic,
        public bool $isProtected,
        public bool $isPrivate,
        public bool $isStatic,
        public bool $isAbstract,
        public bool $isFinal,
        public bool $isConstructor,
        public bool $isDestructor,
        public ?TypeData $returnType,
        /** @var ParameterData[] */
        public array $parameters,
        /** @var AttributeData[] */
        public array $attributes,
    ) {}

    public static function fromReflection(\ReflectionMethod $method): self
    {
        return new self(
            name:          $method->getName(),
            isPublic:      $method->isPublic(),
            isProtected:   $method->isProtected(),
            isPrivate:     $method->isPrivate(),
            isStatic:      $method->isStatic(),
            isAbstract:    $method->isAbstract(),
            isFinal:       $method->isFinal(),
            isConstructor: $method->isConstructor(),
            isDestructor:  $method->isDestructor(),
            returnType:    self::resolveType($method),
            parameters:    self::resolveParameters($method),
            attributes:    self::resolveAttributes($method),
        );
    }

    public function hasReturnType(): bool
    {
        return $this->returnType !== null;
    }

    public function visibility(): string
    {
        return match (true) {
            $this->isPublic    => 'public',
            $this->isProtected => 'protected',
            $this->isPrivate   => 'private',
        };
    }

    public function toString(): string
    {
        $parts = [$this->visibility()];

        if ($this->isAbstract) {
            $parts[] = 'abstract';
        }

        if ($this->isFinal) {
            $parts[] = 'final';
        }

        if ($this->isStatic) {
            $parts[] = 'static';
        }

        $params = implode(', ', array_map(
            fn(ParameterData $p) => $p->toString(),
            $this->parameters,
        ));

        $signature = 'function ' . $this->name . '(' . $params . ')';

        if ($this->returnType !== null) {
            $signature .= ': ' . $this->returnType->toString();
        }

        $parts[] = $signature;

        return implode(' ', $parts);
    }

    private static function resolveType(\ReflectionMethod $method): ?TypeData
    {
        $type = $method->getReturnType();

        if ($type === null) {
            return null;
        }

        return match (true) {
            $type instanceof \ReflectionNamedType        => TypeData::fromNamed(
                $type->getName(),
                $type->allowsNull(),
            ),
            $type instanceof \ReflectionUnionType        => TypeData::fromUnion(
                array_map(
                    fn(\ReflectionNamedType $t) => $t->getName(),
                    $type->getTypes(),
                ),
                $type->allowsNull() ?? false,
            ),
            $type instanceof \ReflectionIntersectionType => TypeData::fromIntersection(
                array_map(
                    fn(\ReflectionNamedType $t) => $t->getName(),
                    $type->getTypes(),
                ),
            ),
            default => null,
        };
    }

    /**
     * @return ParameterData[]
     */
    private static function resolveParameters(\ReflectionMethod $method): array
    {
        return array_map(
            fn(\ReflectionParameter $p) => ParameterData::fromReflection($p),
            $method->getParameters(),
        );
    }

    /**
     * @return AttributeData[]
     */
    private static function resolveAttributes(\ReflectionMethod $method): array
    {
        return array_map(
            fn(\ReflectionAttribute $a) => AttributeData::fromReflection($a),
            $method->getAttributes(),
        );
    }
}