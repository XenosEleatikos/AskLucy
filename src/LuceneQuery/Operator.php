<?php

namespace LuceneQuery;

/**
 * A logical operator
 */
class Operator implements ExpressionInterface
{
    /**
     * The symbol for the and-operator
     *
     * @var string
     */
    public const SYMBOL_AND = 'AND';

    /**
     * The symbol for the or-operator
     *
     * @var string
     */
    public const SYMBOL_OR = 'OR';

    /**
     * A list of valid symbols for logical operators.
     *
     * @var array
     */
    private const SYMBOLS = [
        self::SYMBOL_AND,
        self::SYMBOL_OR
    ];
    /**
     * A sign for the operator
     *
     * @var string
     */
    private $symbol;

    /**
     * Constructs a logical operator.
     *
     * @param string $symbol A symbol for a logical operator
     *
     * @throws \Exception Exception for an invalid logical operator.
     */
    public function __construct(string $symbol)
    {
        if (in_array($symbol, self::SYMBOLS)) {
            $this->symbol = $symbol;
        } else {
            throw new \Exception('Invalid logical operator "' . $symbol . '"!');
        }
    }

    /**
     * Returns the symbol for the logical operator.
     *
     * @return string
     */
    public function __toString(): string
    {
        return $this->symbol;
    }
}
