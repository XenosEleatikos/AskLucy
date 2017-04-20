<?php

namespace LuceneQuery;

/**
 * A term
 */
class Term
{
    /**
     * The search string
     *
     * @var string
     */
    private $searchString;

    /**
     * Constructs a term.
     *
     * @param string $searchString The string to search for
     */
    public function __construct(string $searchString)
    {
        $searchString = trim($searchString);

        $searchString = $this->addQuotesToPhrase($searchString);

        $this->searchString = $searchString;
    }

    /**
     * Returns the search string.
     *
     * @return string
     */
    public function __toString(): string
    {
        return $this->searchString;
    }

    /**
     * Surrounds phrases containing several words with quotes.
     *
     * @param string $searchString A search string
     *
     * @return string
     */
    private function addQuotesToPhrase(string $searchString): string
    {
        if (strpos($searchString, ' ')) {
            $searchString = '"' . $searchString . '"';
        }

        return $searchString;
    }
}
