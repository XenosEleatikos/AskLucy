<?php

declare(strict_types=1);

namespace AskLucy\Expression;

use AskLucy\Escape;

final readonly class Field implements Expression
{
    public const DEFAULT = '';

    public const FIELD_SEPARATOR = ':';

    public function __construct(private string $name = self::DEFAULT) {}

    public function __toString(): string
    {
        return self::DEFAULT === $this->name
            ? self::DEFAULT
            : Escape::field($this->name) . self::FIELD_SEPARATOR;
    }
}
