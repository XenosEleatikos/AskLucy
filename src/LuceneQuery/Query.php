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
     * Adds a sub query to the query.
     *
     * @param QueryInterface $query A query
     */
    public function _add(QueryInterface $query): void
    {
        $this->add($query);
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
        $this->add($query, new Operator('AND'));
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
        $this->add($query, new Operator('OR'));
    }

    /**
     * Appends a sub query with logical operator to the query.
     *
     * @param QueryInterface $query    A query
     * @param Operator       $operator A logical operator
     *
     * @return void
     */
    private function add(QueryInterface $query, Operator $operator = null): void
    {
        if (isset($operator)) {
            $this->elements[] = $operator;
        }

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
