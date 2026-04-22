<?php

declare(strict_types=1);

namespace Rcalicdan\ReflectionSerializer\Data;

use Rcalicdan\ReflectionSerializer\Interfaces\ReflectionMethodInterface;
use Rcalicdan\ReflectionSerializer\Interfaces\ReflectionTypeInterface;
use Rcalicdan\ReflectionSerializer\Internal\TypeResolver;

readonly class MethodData implements ReflectionMethodInterface
{
    public function __construct(
        private string $name,
        private bool $public,
        private bool $protected,
        private bool $private,
        private bool $static,
        private bool $abstract,
        private bool $final,
        private bool $constructor,
        private bool $destructor,
        private ?ReflectionTypeInterface $returnType,
        /**
         * @var ParameterData[]
         */
        private array $parameters,
        /**
         * @var AttributeData[]
         */
        private array $attributes,
    ) {
    }

    public static function fromReflection(\ReflectionMethod $method): self
    {
        return new self(
            name:        $method->getName(),
            public:      $method->isPublic(),
            protected:   $method->isProtected(),
            private:     $method->isPrivate(),
            static:      $method->isStatic(),
            abstract:    $method->isAbstract(),
            final:       $method->isFinal(),
            constructor: $method->isConstructor(),
            destructor:  $method->isDestructor(),
            returnType:  TypeResolver::resolve(
                $method->getReturnType(),
                $method->getReturnType()?->allowsNull() ?? false,
            ),
            parameters:  array_map(
                fn (\ReflectionParameter $p) => ParameterData::fromReflection($p),
                $method->getParameters(),
            ),
            attributes:  array_map(
                fn (\ReflectionAttribute $a) => AttributeData::fromReflection($a),
                $method->getAttributes(),
            ),
        );
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function isPublic(): bool
    {
        return $this->public;
    }

    public function isProtected(): bool
    {
        return $this->protected;
    }

    public function isPrivate(): bool
    {
        return $this->private;
    }

    public function isStatic(): bool
    {
        return $this->static;
    }

    public function isAbstract(): bool
    {
        return $this->abstract;
    }

    public function isFinal(): bool
    {
        return $this->final;
    }

    public function isConstructor(): bool
    {
        return $this->constructor;
    }

    public function isDestructor(): bool
    {
        return $this->destructor;
    }

    public function hasReturnType(): bool
    {
        return $this->returnType !== null;
    }

    public function getReturnType(): ?ReflectionTypeInterface
    {
        return $this->returnType;
    }

    /**
     * @return ParameterData[]
     */
    public function getParameters(): array
    {
        return $this->parameters;
    }

    /**
     * @return AttributeData[]
     */
    public function getAttributes(): array
    {
        return $this->attributes;
    }

    // --- Helpers ---

    public function visibility(): string
    {
        return match (true) {
            $this->public => 'public',
            $this->protected => 'protected',
            $this->private => 'private',
        };
    }

    public function toString(): string
    {
        $parts = [$this->visibility()];

        if ($this->abstract) {
            $parts[] = 'abstract';
        }

        if ($this->final) {
            $parts[] = 'final';
        }

        if ($this->static) {
            $parts[] = 'static';
        }

        $params = implode(', ', array_map(
            fn (ParameterData $p) => $p->toString(),
            $this->parameters,
        ));

        $signature = 'function ' . $this->name . '(' . $params . ')';

        if ($this->returnType !== null) {
            $signature .= ': ' . $this->returnType;
        }

        $parts[] = $signature;

        return implode(' ', $parts);
    }
}
