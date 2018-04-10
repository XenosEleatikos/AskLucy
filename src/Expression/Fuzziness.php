<?php
namespace AskLucy\Expression;

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
     * The Damerau-Levenshtein distance
     * Possible values: 0, 1, 2
     *
     * @var int
     */
    private $fuzziness = 0;

    /**
     * Constructs a logical operator.
     *
     * @param int $distance The Damerau-Levenshtein distance
     *                      Possible values: 0, 1, 2
     */
    private function __construct(int $distance)
    {
        $this->fuzziness = $distance;
    }

    /**
     * Returns the Damerau-Levenshtein distance 0.
     *
     * @return self
     */
    public static function distance0(): self
    {
        return new self(0);
    }

    /**
     * Returns the Damerau-evenshtein distance 1.
     *
     * @return self
     */
    public static function distance1(): self
    {
        return new self(1);
    }

    /**
     * Returns the Damerau-Levenshtein distance 2.
     *
     * @return self
     */
    public static function distance2(): self
    {
        return new self(2);
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
