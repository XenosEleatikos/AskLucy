<?php

namespace LuceneQuery;

/**
 * A term
 */
class Term implements Clause, ExpressionInterface
{
    /**
     * The field
     *
     * @var Field
     */
    private $field;

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
        $this->searchString = trim($searchString);
        $this->field        = new Field;
    }

    /**
     * Adds a field to search in.
     *
     * @param string $name A field name
     */
    public function addField(string $name = ''): void
    {
        $this->field = new Field($name);
    }

    /**
     * Allows search results similar to the search term.
     *
     * @param int $distance The Damerau-Levenshtein Distance
     */
    public function fuzzify(int $distance = 2): void
    {
        if ($distance === 2) {
            $this->appendToEachWord('~');
        } elseif ($distance === 1) {
            $this->appendToEachWord('~1');
        }
    }

    /**
     * Returns true, if the search string is a phrase of several words, otherwise false.
     *
     * @return bool
     */
    public function isPhrase(): bool
    {
        return (strpos($this->searchString, ' ')) ? true : false;
    }

    /**
     * Returns the search string.
     *
     * @return string
     */
    public function __toString(): string
    {
        $term = (empty((string) $this->field))
            ? ''
            : (string) $this->field . ':';

        $term .= ($this->isPhrase())
            ? $this->addQuotes($this->searchString)
            : $this->searchString;

        return $term;
    }

    /**
     * Surrounds a given search string with quotes.
     *
     * @return string
     */
    private function addQuotes($searchString): string
    {
        return '"' . $searchString . '"';
    }

    /**
     * Appends a given string to each word of the search string.
     *
     * @param string $string A string to append
     *
     * @return void
     */
    private function appendToEachWord(string $string): void
    {
        $this->searchString = implode(
            ' ',
            array_map(
                function (string $word) use ($string)
                {
                    return $word . $string;
                },
                explode(' ', $this->searchString)
            )
        );
    }
}
