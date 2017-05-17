<?php

namespace LuceneQuery;

/**
 * A logical operator
 */
class Operator implements Expression
{
    /**
     * The and-operator
     *
     * @var string
     */
    public const SYMBOL_AND = 'AND ';

    /**
     * The or-operator
     *
     * @var string
     */
    public const SYMBOL_OR = 'OR ';

    /**
     * The not-operator
     *
     * @var string
     */
    public const SYMBOL_NOT = 'NOT ';

    /**
     * The "optional"-operator
     *
     * @var string
     */
    public const SYMBOL_OPTIONAL = '';

    /**
     * The "prohibited"-operator
     *
     * @var string
     */
    public const SYMBOL_PROHIBITED = '-';

    /**
     * The "required"-operator
     *
     * @var string
     */
    public const SYMBOL_REQUIRED = '+';

    /**
     * A list of valid operators
     *
     * @var array
     */
    private const SYMBOLS = [
        self::SYMBOL_AND,
        self::SYMBOL_OR,
        self::SYMBOL_NOT,
        self::SYMBOL_OPTIONAL,
        self::SYMBOL_PROHIBITED,
        self::SYMBOL_REQUIRED
    ];

    /**
     * The operator
     *
     * @var string
     */
    private $symbol;

    /**
     * Constructs a logical operator.
     *
     * @param string $symbol A logical operator
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
     * Returns the logical operator as string.
     *
     * @return string
     */
    public function __toString(): string
    {
        return $this->symbol;
    }
}
