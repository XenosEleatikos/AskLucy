<?php
namespace AskLucy;

/**
 * A logical operator
 */
class RangeType
{
    /**
     * Inclusive
     *
     * @var string
     */
    private const INCLUSIVE = 'inclusive';

    /**
     * Exclusive
     *
     * @var string
     */
    private const EXCLUSIVE = 'exclusive';

    /**
     * Opening bracket for inclusive range
     *
     * @var string
     */
    private const BRACKET_OPENING_INCLUSIVE = '[';

    /**
     * Closing bracket for inclusive range
     *
     * @var string
     */
    private const BRACKET_CLOSING_INCLUSIVE = ']';

    /**
     * Opening bracket for exclusive range
     *
     * @var string
     */
    private const BRACKET_OPENING_EXCLUSIVE = '{';

    /**
     * Closing bracket for exclusive range
     *
     * @var string
     */
    private const BRACKET_CLOSING_EXCLUSIVE = '}';

    /**
     * The code of the range type
     *
     * @var string
     */
    private $rangeTypeCode;

    /**
     * Constructs a range type.
     *
     * @param string $rangeTypeCode A range type code
     */
    private function __construct(string $rangeTypeCode)
    {
        $this->rangeTypeCode = $rangeTypeCode;
    }

    /**
     * Returns the inclusive range.
     *
     * @return self
     */
    public static function inclusive(): self
    {
        return new self(self::INCLUSIVE);
    }

    /**
     * Returns the exclusive range.
     *
     * @return self
     */
    public static function exclusive(): self
    {
        return new self(self::EXCLUSIVE);
    }

    /**
     * Returns the opening bracket.
     *
     * @return string
     */
    public function getOpeningBracket(): string
    {
        return (self::INCLUSIVE === $this->rangeTypeCode)
            ? self::BRACKET_OPENING_INCLUSIVE
            : self::BRACKET_OPENING_EXCLUSIVE;
    }

    /**
     * Returns the closing bracket.
     *
     * @return string
     */
    public function getClosingBracket(): string
    {
        return (self::INCLUSIVE === $this->rangeTypeCode)
            ? self::BRACKET_CLOSING_INCLUSIVE
            : self::BRACKET_CLOSING_EXCLUSIVE;
    }
}
