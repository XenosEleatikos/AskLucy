<?php

namespace LuceneQuery;

/**
 * A term
 */
class Term implements QueryInterface, ExpressionInterface
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

        if ($this->isPhrase()) {
            $this->addQuotes();
        }
    }

    /**
     * Adds a field to search in.
     *
     * @param string $name A field name
     */
    public function addField(string $name = '')
    {
        $this->field = new Field($name);
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
        return (empty((string) $this->field))
            ? (string) $this->searchString
            : (string) $this->field . ':' . $this->searchString;
    }

    /**
     * Surrounds the search string with quotes.
     *
     * @return void
     */
    private function addQuotes(): void
    {
        $this->searchString = '"' . $this->searchString . '"';
    }
}
