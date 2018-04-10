<?php
namespace AskLucy\Property;

use AskLucy\Expression\Boost;

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
     * @return void
     *
     * @throws \Exception Thrown, if the given value is negative
     */
    public function boost(float $value): void
    {
        $this->boost = new Boost($value);
    }
}
