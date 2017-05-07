<?php

namespace LuceneQuery;

use Doctrine\DBAL\Driver\AbstractDriverException;
use LuceneQuery\Property\OperatorTrait;

/**
 * A term
 */
class Term implements Clause
{
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
     *
     * @throws \Exception Throws an exception, if the given string contains spaces.
     */
    public function __construct(string $searchString)
    {
        $searchString = trim($searchString);

        if (strpos($searchString, Phrase::TERM_SEPARATOR)) {
            throw new \Exception('A term must not contain spaces.');
        }

        $this->searchString = trim($searchString);
        $this->fuzziness    = new Fuzziness(0);
    }

    /**
     * Allows search results similar to the search term.
     *
     * @param int $distance The Damerau-Levenshtein Distance
     *                      Possible values: 0, 1, 2
     *
     * @throws \Exception Thrown, if the given Damerau-Levenshtein Distance is out of range
     *
     * @return self
     */
    public function fuzzify(int $distance = 2): self
    {
        $this->fuzziness->setDistance($distance);

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
            . $this->searchString
            . $this->fuzziness;
    }
}
