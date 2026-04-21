<?php

declare(strict_types=1);

namespace Rcalicdan\ReflectionSerializer\Data;

final readonly class AttributeData
{
    public function __construct(
        public string $name,      
        public int $target,       
        public array $arguments,  
    ) {}

    public static function fromReflection(\ReflectionAttribute $attribute): self
    {
        return new self(
            name: $attribute->getName(),
            target: $attribute->getTarget(),
            arguments: $attribute->getArguments(),
        );
    }

    /**
     * Attempts to instantiate the actual attribute object.
     * This is optional and the consumer decides when/if to do this.
     */
    public function newInstance(): object
    {
        return new ($this->name)(...$this->arguments);
    }

    public function isTargeting(int $target): bool
    {
        return (bool) ($this->target & $target);
    }
}