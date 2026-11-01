<?php

declare(strict_types=1);

namespace AskLucy\Expression;

use AskLucy\Exception\InvalidArgumentException;

final readonly class Proximity implements Expression
{
    private const PROXIMITY_OPERATOR = '~';

    public function __construct(private int $proximity = 0)
    {
        if ($proximity < 0) {
            throw new InvalidArgumentException('The given term distance must not be negative!');
        }
    }

    public function __toString(): string
    {
        return $this->proximity > 0
            ? self::PROXIMITY_OPERATOR . $this->proximity
            : '';
    }
}
