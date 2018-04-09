<?php
namespace AskLucy;

use AskLucy\Property\BoostTrait;
use AskLucy\Property\FieldTrait;
use AskLucy\Property\OperatorTrait;

/**
 * A range
 */
class Range implements Clause
{
    use BoostTrait;
    use FieldTrait;
    use OperatorTrait;

    /**
     * The "to"-operator
     *
     * @var string
     */
    private const TO = ' TO ';

    /**
     * The lower bound
     *
     * @var Term
     */
    private $from;

    /**
     * The upper bound
     *
     * @var Term
     */
    private $to;

    /**
     * The type of the range
     *
     * @var RangeType
     */
    private $rangeType;

    /**
     * Constructs a range.
     *
     * @param string $from  The lower bound
     * @param string $to    The upper bound
     * @param string $field Optional name of the field to search in
     *
     * @throws \Exception Throws an exception, if the given string contains spaces.
     */
    public function __construct(string $from, string $to, string $field = Field::DEFAULT)
    {
        $this->from      = new Term($from);
        $this->to        = new Term($to);
        $this->field     = new Field($field);
        $this->rangeType = RangeType::inclusive();
    }

    /**
     * Includes the lower and the upper bound.
     *
     * @return self
     */
    public function inclusive(): self
    {
        $this->rangeType = RangeType::inclusive();

        return $this;
    }

    /**
     * Excludes the lower and the upper bound.
     *
     * @return self
     */
    public function exclusive(): self
    {
        $this->rangeType = RangeType::exclusive();

        return $this;
    }

    /**
     * Returns the range as string.
     *
     * @return string
     */
    public function __toString(): string
    {
        return $this->operator
            . $this->field
            . $this->rangeType->getOpeningBracket()
            . $this->from
            . self::TO
            . $this->to
            . $this->rangeType->getClosingBracket()
            . $this->boost;
    }
}
