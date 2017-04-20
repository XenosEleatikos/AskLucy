<?php

namespace LuceneQuery;

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
}
