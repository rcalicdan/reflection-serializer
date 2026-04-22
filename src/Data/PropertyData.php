<?php

declare(strict_types=1);

namespace Rcalicdan\ReflectionSerializer\Data;

use Rcalicdan\ReflectionSerializer\Interfaces\ReflectionPropertyInterface;
use Rcalicdan\ReflectionSerializer\Interfaces\ReflectionTypeInterface;
use Rcalicdan\ReflectionSerializer\Internal\TypeResolver;
use Rcalicdan\ReflectionSerializer\Internal\ValueExporter;

readonly class PropertyData implements ReflectionPropertyInterface
{
    public function __construct(
        private string $name,
        private ?ReflectionTypeInterface $type,
        private bool $public,
        private bool $protected,
        private bool $private,
        private bool $static,
        private bool $readonly,
        private bool $promoted,
        private bool $hasDefault,
        private mixed $defaultValue,
        /**
         * @var AttributeData[]
         */
        private array $attributes,
    ) {
    }

    public static function fromReflection(\ReflectionProperty $property): self
    {
        [$hasDefault, $defaultValue] = self::resolveDefault($property);

        return new self(
            name: $property->getName(),
            type: TypeResolver::resolve($property->getType()),
            public: $property->isPublic(),
            protected: $property->isProtected(),
            private: $property->isPrivate(),
            static: $property->isStatic(),
            readonly: $property->isReadOnly(),
            promoted: $property->isPromoted(),
            hasDefault: $hasDefault,
            defaultValue: $defaultValue,
            attributes: array_map(
                fn (\ReflectionAttribute $a) => AttributeData::fromReflection($a),
                $property->getAttributes(),
            ),
        );
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function hasType(): bool
    {
        return $this->type !== null;
    }

    public function getType(): ?ReflectionTypeInterface
    {
        return $this->type;
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

    public function isReadOnly(): bool
    {
        return $this->readonly;
    }

    public function isPromoted(): bool
    {
        return $this->promoted;
    }

    public function hasDefaultValue(): bool
    {
        return $this->hasDefault;
    }

    public function getDefaultValue(): mixed
    {
        return $this->defaultValue;
    }

    /**
     * @return AttributeData[]
     */
    public function getAttributes(): array
    {
        return $this->attributes;
    }

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

        if ($this->static) {
            $parts[] = 'static';
        }

        if ($this->readonly) {
            $parts[] = 'readonly';
        }

        if ($this->type !== null) {
            $parts[] = (string) $this->type;
        }

        $parts[] = '$' . $this->name;

        if ($this->hasDefault && ! $this->promoted) {
            $parts[] = '= ' . ValueExporter::export($this->defaultValue);
        }

        return implode(' ', $parts);
    }

    private static function resolveDefault(\ReflectionProperty $property): array
    {
        if ($property->isPromoted()) {
            return [false, null];
        }

        if (! $property->hasDefaultValue()) {
            return [false, null];
        }

        return [true, $property->getDefaultValue()];
    }
}
