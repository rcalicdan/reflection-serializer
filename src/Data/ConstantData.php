<?php

declare(strict_types=1);

namespace Rcalicdan\ReflectionSerializer\Data;

use Rcalicdan\ReflectionSerializer\Interfaces\ReflectionClassConstantInterface;
use Rcalicdan\ReflectionSerializer\Internal\ValueExporter;

readonly class ConstantData implements ReflectionClassConstantInterface
{
    public function __construct(
        private string $name,
        private mixed $value,
        private bool $public,
        private bool $protected,
        private bool $private,
        private bool $final,
        /**
         * @var AttributeData[]
         */
        private array $attributes,
    ) {
    }

    public static function fromReflection(\ReflectionClassConstant $constant): self
    {
        return new self(
            name:      $constant->getName(),
            value:     $constant->getValue(),
            public:    $constant->isPublic(),
            protected: $constant->isProtected(),
            private:   $constant->isPrivate(),
            final:     $constant->isFinal(),
            attributes: array_map(
                fn (\ReflectionAttribute $a) => AttributeData::fromReflection($a),
                $constant->getAttributes(),
            ),
        );
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getValue(): mixed
    {
        return $this->value;
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

    public function isFinal(): bool
    {
        return $this->final;
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

        if ($this->final) {
            $parts[] = 'final';
        }

        $parts[] = 'const';
        $parts[] = $this->name;
        $parts[] = '=';
        $parts[] = ValueExporter::export($this->value);

        return implode(' ', $parts);
    }
}
