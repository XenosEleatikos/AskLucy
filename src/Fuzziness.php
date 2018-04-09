<?php
namespace AskLucy;

/**
 * The fuzziness
 *
 * @see https://en.wikipedia.org/wiki/Damerau%E2%80%93Levenshtein_distance
 */
class Fuzziness implements Expression
{
    /**
     * The fuzziness operator
     *
     * @var string
     */
    private const FUZZINESS_OPERATOR = '~';

    /**
     * The Damerau-Levenshtein Distance
     * Possible values: 0, 1, 2
     *
     * @var int
     */
    private $fuzziness = 0;

    /**
     * Constructs a logical operator.
     *
     * @param int $distance The Damerau-Levenshtein Distance
     *                      Possible values: 0, 1, 2
     *
     * @throws \Exception Thrown, if the given Damerau-Levenshtein Distance is out of range
     */
    public function __construct(int $distance)
    {
        if ($distance >= 0 && $distance <= 2) {
            $this->fuzziness = $distance;
        } else {
            throw new \Exception('The Damerau-Levenshtein Distance must be 0, 1 or 2.');
        }
    }

    /**
     * Sets the Damerau-Levenshtein Distance.
     *
     * @param int $distance The Damerau-Levenshtein Distance
     *                      Possible values: 0, 1, 2
     *
     * @throws \Exception
     *
     * @return self
     */
    public function setDistance(int $distance = 2): self
    {
        if ($distance >= 0 && $distance <= 2) {
            $this->fuzziness = $distance;

            return $this;
        }

        throw new \Exception('The Damerau-Levenshtein Distance must be 0, 1 or 2.');
    }

    /**
     * Returns the fuzziness as string.
     *
     * @return string
     */
    public function __toString(): string
    {
        switch ($this->fuzziness) {
            case 2:
                return self::FUZZINESS_OPERATOR;
            case 1:
                return self::FUZZINESS_OPERATOR . '1';
            default:
                return '';
        }
    }
}
