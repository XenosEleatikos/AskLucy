<?php
namespace AskLucy\Expression\Clause;

use AskLucy\Expression\Field;
use AskLucy\Expression\Fuzziness;
use AskLucy\Property\BoostTrait;
use AskLucy\Property\FieldTrait;
use AskLucy\Property\OperatorTrait;

/**
 * A term
 */
class Term implements Clause
{
    use BoostTrait;
    use FieldTrait;
    use OperatorTrait;

    /**
     * The search string
     *
     * @var string
     */
    private $searchString;

    /**
     * The fuzziness
     *
     * @var Fuzziness
     */
    private $fuzziness;

    /**
     * Constructs a term.
     *
     * @param string $searchString The string to search for
     * @param string $field        Optional name of the field to search in
     *
     * @throws \Exception Throws an exception, if the given string contains spaces.
     */
    public function __construct(string $searchString, string $field = Field::DEFAULT)
    {
        $searchString = trim($searchString);

        if (strpos($searchString, Phrase::TERM_SEPARATOR)) {
            throw new \Exception('A term must not contain spaces.');
        }

        $this->searchString = trim($searchString);
        $this->field        = new Field($field);
        $this->fuzziness    = Fuzziness::distance0();
    }

    /**
     * Allows search results similar to the search term.
     *
     * @param int $distance The Damerau-Levenshtein distance
     *                      Possible values: 0, 1, 2
     *
     * @return self
     */
    public function fuzzify(int $distance = 2): self
    {
        if ($distance < 1) {
            return $this->fuzzify0();
        } elseif ($distance === 1) {
            return $this->fuzzify1();
        } else {
            return $this->fuzzify2();
        }
    }

    /**
     * Sets the Damerau-Levenshtein distance 0 as fuzziness.
     *
     * @return self
     */
    public function fuzzify0(): self
    {
        $this->fuzziness = Fuzziness::distance0();

        return $this;
    }

    /**
     * Sets the Damerau-Levenshtein distance 1 as fuzziness.
     *
     * @return self
     */
    public function fuzzify1(): self
    {
        $this->fuzziness = Fuzziness::distance1();

        return $this;
    }

    /**
     * Sets the Damerau-Levenshtein distance 2 as fuzziness.
     *
     * @return self
     */
    public function fuzzify2(): self
    {
        $this->fuzziness = Fuzziness::distance2();

        return $this;
    }

    /**
     * Returns the search string.
     *
     * @return string
     */
    public function __toString(): string
    {
        return $this->operator
            . $this->field
            . $this->searchString
            . $this->fuzziness
            . $this->boost;
    }
}
