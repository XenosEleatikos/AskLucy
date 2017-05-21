<?php

namespace LuceneQuery\Property;

use LuceneQuery\Boost;

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
     */
    public function boost(float $value): void
    {
        $this->boost = new Boost((float) $value);
    }
}
