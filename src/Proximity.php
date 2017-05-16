<?php

namespace LuceneQuery;

/**
 * The term proximity
 */
class Proximity implements Expression
{
    /**
     * The proximity operator
     *
     * @var string
     */
    const PROXIMITY_OPERATOR = '~';

    /**
     * The required proximity between the terms
     *
     * @var int
     */
    private $proximity = 0;

    /**
     * Constructs the term proximity.
     *
     * @param int $proximity The required proximity between the terms
     *
     * @throws \Exception Thrown, if the given distance is negative, what makes no sense
     */
    public function __construct(int $proximity = 0)
    {
        if ($proximity >= 0) {
            $this->proximity = $proximity;

            return;
        }

        throw new \Exception('The given term distance must be positive!');
    }

    /**
     * Returns the term proximity as integer.
     *
     * @return int
     */
    public function getDistance(): int
    {
        return $this->proximity;
    }

    /**
     * Returns the term proximity as string.
     *
     * @return string
     */
    public function __toString(): string
    {
        return (string) $this->proximity;
    }
}
