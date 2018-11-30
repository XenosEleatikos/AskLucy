<?php
namespace AskLucy\Expression;

use AskLucy\Exception\InvalidArgumentException;

/**
 * A boost
 */
class Boost implements Expression
{
    /**
     * The boost operator
     *
     * @var string
     */
    private const BOOST_OPERATOR = '^';

    /**
     * The default boost value
     *
     * @var float
     */
    private const DEFAULT_VALUE = 1.0;

    /**
     * The boost value
     *
     * @var float
     */
    private $value = 0.0;

    /**
     * Constructs the boost.
     *
     * @param float $value The boost value
     *
     * @throws InvalidArgumentException Thrown, if the given value is negative
     */
    public function __construct(float $value = self::DEFAULT_VALUE)
    {
        if ($value > 0) {
            $this->value = $value;

            return;
        }

        throw new InvalidArgumentException('The given boost value must be positive!');
    }

    /**
     * Returns the boost value as string.
     * We can ignore the boost for the default value 1.
     *
     * @return string
     */
    public function __toString(): string
    {
        return ($this->value === self::DEFAULT_VALUE)
            ? ''
            : self::BOOST_OPERATOR . $this->value;
    }
}
