<?php
namespace AskLucy\Property;

use AskLucy\Exception\InvalidArgumentException;
use AskLucy\Expression\Boost;
use AskLucy\Expression\Clause\Clause;

/**
 * Provides accessors for the boost.
 */
trait BoostTrait
{
    /**
     * The boost
     *
     * @var Boost
     */
    private $boost;

    /**
     * Boosts the clause.
     *
     * @param float $value The boost value
     *
     * @return self|static
     *
     * @throws InvalidArgumentException Thrown, if the given value is negative
     */
    public function boost(float $value): Clause
    {
        $this->boost = new Boost($value);

        return $this;
    }
}
