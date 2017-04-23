<?php

namespace LuceneQuery;

/**
 * A phrase
 */
class Phrase extends AbstractClause
{
    /**
     * A list of terms
     *
     * @var Term[]
     */
    private $terms = [];

    /**
     * Constructs a phrase.
     *
     * @param string $searchPhrase The phrase to search for
     */
    public function __construct(string $searchPhrase)
    {
        parent::__construct();

        $this->addTerms($searchPhrase);
    }

    /**
     * Allows search results similar to the search term.
     *
     * @param int $distance The Damerau-Levenshtein Distance
     *
     * @return self
     */
    public function fuzzify(int $distance = 2): self
    {
        foreach ($this->terms as $term) {
            $term->fuzzify($distance);
        }

        return $this;
    }

    /**
     * Returns the phrase as string.
     *
     * @return string
     */
    public function __toString(): string
    {
        $term = $this->getFieldSpecification()
            . $this->getSearchString();

        return $term;
    }

    /**
     * Creates a term object for each word of a given phrase and appends it to the list.
     *
     * @param string $searchPhrase A search phrase
     *
     * @return void
     */
    private function addTerms(string $searchPhrase): void
    {
        $this->terms = array_merge(
            $this->terms,
            array_map(
                function (string $searchTerm)
                {
                    return new Term($searchTerm);
                },
                explode(' ', $searchPhrase)
            )
        );
    }

    /**
     * Returns the search string.
     *
     * @return string
     */
    private function getSearchString(): string
    {
        return '"' . implode(' ', $this->terms) . '"';
    }
}
