<?php

namespace LuceneQuery;

use LuceneQuery\Property\FieldTrait;
use LuceneQuery\Property\OperatorTrait;

/**
 * A phrase
 */
class Phrase implements Clause
{
    use FieldTrait;
    use OperatorTrait;

    /**
     * Separator between terms
     *
     * @var string
     */
    const TERM_SEPARATOR = ' ';

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
     * @param string $field        Optional name of the field to search in
     */
    public function __construct(string $searchPhrase, string $field = Field::DEFAULT)
    {
        $this->addTerms($searchPhrase);
        $this->field = new Field($field);
    }

    /**
     * Returns the phrase as string.
     *
     * @return string
     */
    public function __toString(): string
    {
        $terms = implode(self::TERM_SEPARATOR, $this->terms);
        $terms = (count($this->terms) > 1)
            ? '"' . $terms . '"'
            : $terms;

        return $this->getFieldSpecification() . $terms;
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
                explode(self::TERM_SEPARATOR, $searchPhrase)
            )
        );
    }
}
