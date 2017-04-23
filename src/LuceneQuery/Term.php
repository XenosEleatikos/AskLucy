<?php

namespace LuceneQuery;

/**
 * A term
 */
class Term extends AbstractClause
{
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
     * @throws \Exception
     */
    public function __construct(string $searchString)
    {
        $this->searchString = trim($searchString);
        $this->fuzziness    = new Fuzziness(0);

        if (strpos($this->searchString, ' ')) {
            throw new \Exception('A term must not contain spaces.');
        }

        parent::__construct();
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
        return $this->getFieldSpecification()
            . $this->searchString
            . $this->fuzziness;
    }
}
