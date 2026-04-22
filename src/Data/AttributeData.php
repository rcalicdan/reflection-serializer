<?php

declare(strict_types=1);

namespace Rcalicdan\ReflectionSerializer\Data;

use Rcalicdan\ReflectionSerializer\Interfaces\ReflectionAttributeInterface;

readonly class AttributeData implements ReflectionAttributeInterface
{
    public function __construct(
        private string $name,
        private int $target,
        private array $arguments,
    ) {}

    public static function fromReflection(\ReflectionAttribute $attribute): self
    {
        return new self(
            name:      $attribute->getName(),
            target:    $attribute->getTarget(),
            arguments: $attribute->getArguments(),
        );
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getTarget(): int
    {
        return $this->target;
    }

    public function getArguments(): array
    {
        return $this->arguments;
    }

    public function newInstance(): object
    {
        return new ($this->name)(...$this->arguments);
    }

    public function isTargeting(int $target): bool
    {
        return (bool) ($this->target & $target);
    }
}