<?php

declare(strict_types=1);

namespace Rcalicdan\ReflectionSerializer\Data;

readonly class ConstantData
{
    public function __construct(
        public string $name,
        public mixed $value,
        public bool $isPublic,
        public bool $isProtected,
        public bool $isPrivate,
        public bool $isFinal,
        /** @var AttributeData[] */
        public array $attributes,
    ) {}

    public static function fromReflection(\ReflectionClassConstant $constant): self
    {
        return new self(
            name:        $constant->getName(),
            value:       $constant->getValue(),
            isPublic:    $constant->isPublic(),
            isProtected: $constant->isProtected(),
            isPrivate:   $constant->isPrivate(),
            isFinal:     $constant->isFinal(),
            attributes:  self::resolveAttributes($constant),
        );
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

        if ($this->isFinal) {
            $parts[] = 'final';
        }

        $parts[] = 'const';
        $parts[] = $this->name;
        $parts[] = '=';
        $parts[] = self::exportValue($this->value);

        return implode(' ', $parts);
    }

    /**
     * @return AttributeData[]
     */
    private static function resolveAttributes(\ReflectionClassConstant $constant): array
    {
        return array_map(
            fn(\ReflectionAttribute $a) => AttributeData::fromReflection($a),
            $constant->getAttributes(),
        );
    }

    private static function exportValue(mixed $value): string
    {
        return match (true) {
            $value === null   => 'null',
            $value === true   => 'true',
            $value === false  => 'false',
            is_string($value) => '"' . addslashes($value) . '"',
            is_array($value)  => '[' . implode(', ', array_map(self::exportValue(...), $value)) . ']',
            default           => (string) $value,
        };
    }
}