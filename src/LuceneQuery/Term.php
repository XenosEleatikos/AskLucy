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
     * The Damerau-Levenshtein Distance
     * Possible values: 0, 1, 2
     *
     * @var int
     */
    private $fuzziness = 0;

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
     * @throws \Exception
     *
     * @return self
     */
    public function fuzzify(int $distance = 2): self
    {
        if ($distance >= 0 && $distance <= 2) {
            $this->fuzziness = $distance;

            return $this;
        }

        throw new \Exception('The Damerau-Levenshtein Distance must be 0, 1 or 2.');
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
            . $this->getFuzzinessSpecification();
    }

    /**
     * Returns the fuzziness specification.
     *
     * @return string
     */
    private function getFuzzinessSpecification(): string
    {
        switch ($this->fuzziness) {
            case 2:
                return '~';
            case 1:
                return '~1';
            default:
                return '';
        }
    }
}
