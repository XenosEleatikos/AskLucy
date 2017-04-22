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
     * Appends a query with optional matching.
     *
     * @param QueryInterface $query A query
     *
     * @return Query
     */
    public function addOptionalQuery(QueryInterface $query): self
    {
        $this->elements[] = new Operator(Operator::SYMBOL_OPTIONAL);
        $this->elements[] = $query;

        return $this;
    }

    /**
     * Appends a query with required matching.
     *
     * @param QueryInterface $query A query
     *
     * @return Query
     */
    public function addRequiredQuery(QueryInterface $query): self
    {
        $this->elements[] = new Operator(Operator::SYMBOL_REQUIRED);
        $this->elements[] = $query;

        return $this;
    }

    /**
     * Appends a query with prohibited matching.
     *
     * @param QueryInterface $query A query
     *
     * @return Query
     */
    public function addProhibitedQuery(QueryInterface $query): self
    {
        $this->elements[] = new Operator(Operator::SYMBOL_PROHIBITED);
        $this->elements[] = $query;

        return $this;
    }

    /**
     * Appends a sub query as and-statement to the query.
     *
     * @param QueryInterface $query A query
     *
     * @return self
     */
    public function _and(QueryInterface $query): self
    {
        $this->elements[] = new Operator(Operator::SYMBOL_AND);
        $this->elements[] = $query;

        return $this;
    }

    /**
     * Appends a sub query as or-statement to the query.
     *
     * @param QueryInterface $query A query
     *
     * @return self
     */
    public function _or(QueryInterface $query): self
    {
        $this->elements[] = new Operator(Operator::SYMBOL_OR);
        $this->elements[] = $query;

        return $this;
    }

    /**
     * Appends a sub query as not-statement to the query.
     *
     * @param QueryInterface $query A query
     *
     * @return self
     */
    public function _not(QueryInterface $query): self
    {
        $this->elements[] = new Operator(Operator::SYMBOL_NOT);
        $this->elements[] = $query;

        return $this;
    }

    /**
     * Returns the query as string.
     *
     * @return string
     */
    public function __toString(): string
    {
        return '(' . implode('' , $this->elements) . ')';
    }
}
