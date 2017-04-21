<?php

namespace LuceneQuery;

class Query implements QueryInterface, ExpressionInterface
{
    /**
     * An array containing queries and operators
     *
     * @var ExpressionInterface[]
     */
    private $elements = [];

    /**
     * Constructs a query.
     *
     * @param QueryInterface $query A query
     */
    public function __construct(QueryInterface $query)
    {
        $this->elements = [$query];
    }

    /**
     * Appends a sub query as and-statement to the query.
     *
     * @param QueryInterface $query A query
     *
     * @return void
     */
    public function _and(QueryInterface $query): void
    {
        $this->elements[] = new Operator('AND');
        $this->elements[] = $query;
    }

    /**
     * Appends a sub query as or-statement to the query.
     *
     * @param QueryInterface $query A query
     *
     * @return void
     */
    public function _or(QueryInterface $query): void
    {
        $this->elements[] = new Operator('OR');
        $this->elements[] = $query;
    }

    /**
     * Returns the query as string.
     *
     * @return string
     */
    public function __toString(): string
    {
        return '(' . implode(' ' , $this->elements) . ')';
    }
}
