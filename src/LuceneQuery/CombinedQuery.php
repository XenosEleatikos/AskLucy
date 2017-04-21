<?php

namespace LuceneQuery;

class CombinedQuery implements QueryInterface, ExpressionInterface
{
    /**
     * An array containing queries and operators alternating
     *
     * @var ExpressionInterface[]
     */
    private $elements = [];

    /**
     * Constructs a combined query.
     *
     * @param QueryInterface $query A query
     */
    public function __construct(QueryInterface $query)
    {
        $this->elements = [$query];
    }

    /**
     * Appends a sub query as and-statement to the combined query.
     *
     * @param QueryInterface $query A query
     */
    public function _and(QueryInterface $query)
    {
        $this->add(new Operator('AND'), $query);
    }

    /**
     * Appends a sub query as or-statement to the combined query.
     *
     * @param QueryInterface $query A query
     */
    public function _or(QueryInterface $query)
    {
        $this->add(new Operator('OR'), $query);
    }

    /**
     * Appends a sub query with logical operator to the combined query.
     *
     * @param Operator       $operator A logical operator
     * @param QueryInterface $query    A query
     */
    private function add(Operator $operator, QueryInterface $query)
    {
        $this->elements[] = $operator;
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
