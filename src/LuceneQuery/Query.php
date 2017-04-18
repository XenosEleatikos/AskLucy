<?php

namespace LuceneQuery;

class Query implements QueryInterface
{
    /**
     * The field
     *
     * @var Field
     */
    private $field;

    /**
     * The search term
     *
     * @var Term
     */
    private $term;

    /**
     * Constructs a primitive query.
     *
     * @param Term       $term  The term
     * @param null|Field $field The field
     */
    public function __construct(Term $term, Field $field = null)
    {
        $this->field = (isset($field)) ? $field : new Field;
        $this->term  = $term;
    }

    /**
     * Returns the query as string.
     *
     * @return string
     */
    public function __toString(): string
    {
        return (!empty((string) $this->field))
            ? (string) $this->field . ':' . (string) $this->term
            : (string) $this->term;
    }
}
