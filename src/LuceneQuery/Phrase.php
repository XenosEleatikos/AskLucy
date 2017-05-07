<?php

namespace LuceneQuery;

use LuceneQuery\Property\OperatorTrait;

/**
 * A phrase
 */
class Phrase implements Clause
{
    use OperatorTrait;

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
        $this->addTerms($searchPhrase);
    }

    /**
     * Returns the phrase as string.
     *
     * @return string
     */
    public function __toString(): string
    {
        $terms = implode(' ', $this->terms);

        return (count($this->terms) > 1)
            ? '"' . $terms . '"'
            : $terms;
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
}
