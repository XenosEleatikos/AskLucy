<?php
namespace AskLucy\Expression;

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
     * The operator
     *
     * @var string
     */
    private $symbol;

    /**
     * Constructs a logical operator.
     *
     * @param string $symbol A logical operator
     */
    private function __construct(string $symbol)
    {
        $this->symbol = $symbol;
    }

    /**
     * Returns the "optional"-operator.
     *
     * @return self
     */
    public static function optional(): self
    {
        return new self(self::SYMBOL_OPTIONAL);
    }

    /**
     * Returns the "prohibited"-operator.
     *
     * @return self
     */
    public static function prohibited(): self
    {
        return new self(self::SYMBOL_PROHIBITED);
    }

    /**
     * Returns the "required"-operator.
     *
     * @return self
     */
    public static function required(): self
    {
        return new self(self::SYMBOL_REQUIRED);
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
