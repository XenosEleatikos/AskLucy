<?php

declare(strict_types=1);

namespace AskLucy\Expression;

use AskLucy\Exception\InvalidArgumentException;

final readonly class Boost implements Expression
{
    private const BOOST_OPERATOR = '^';

    private const DEFAULT_VALUE = 1.0;

    /** The smallest value that is not rendered as zero. */
    private const MIN_VALUE = 0.0000000001;

    public function __construct(private float $value = self::DEFAULT_VALUE)
    {
        if (!is_finite($value) || $value < self::MIN_VALUE) {
            throw new InvalidArgumentException('The given boost value must be positive!');
        }
    }

    public function __toString(): string
    {
        if (self::DEFAULT_VALUE === $this->value) {
            return '';
        }

        return self::BOOST_OPERATOR . self::format($this->value);
    }

    /**
     * Renders the value without exponent and without trailing zeros.
     */
    private static function format(float $value): string
    {
        return rtrim(rtrim(sprintf('%.10F', $value), '0'), '.');
    }
}
