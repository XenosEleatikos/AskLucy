<?php

namespace LuceneQuery;

/**
 * A query
 */
class Query extends AbstractClause
{
    /**
     * An array containing clauses and operators
     *
     * @var Expression[]
     */
    private $elements = [];

    /**
     * Constructs a query.
     *
     * @param Clause $query A clause
     */
    public function __construct(Clause $query)
    {
        $this->elements = [$query];
    }

    /**
     * Appends a clause with optional matching.
     *
     * @param Clause $query A query
     *
     * @return Query
     */
    public function addOptionalClause(Clause $query): self
    {
        $this->elements[] = new Operator(Operator::SYMBOL_OPTIONAL);
        $this->elements[] = $query;

        return $this;
    }

    /**
     * Appends a clause with required matching.
     *
     * @param Clause $query A query
     *
     * @return Query
     */
    public function addRequiredClause(Clause $query): self
    {
        $this->elements[] = new Operator(Operator::SYMBOL_REQUIRED);
        $this->elements[] = $query;

        return $this;
    }

    /**
     * Appends a clause with prohibited matching.
     *
     * @param Clause $query A query
     *
     * @return Query
     */
    public function addProhibitedClause(Clause $query): self
    {
        $this->elements[] = new Operator(Operator::SYMBOL_PROHIBITED);
        $this->elements[] = $query;

        return $this;
    }

    /**
     * Appends a clause as and-statement.
     *
     * @param Clause $query A query
     *
     * @return self
     */
    public function _and(Clause $query): self
    {
        $this->elements[] = new Operator(Operator::SYMBOL_AND);
        $this->elements[] = $query;

        return $this;
    }

    /**
     * Appends a clause as or-statement.
     *
     * @param Clause $query A query
     *
     * @return self
     */
    public function _or(Clause $query): self
    {
        $this->elements[] = new Operator(Operator::SYMBOL_OR);
        $this->elements[] = $query;

        return $this;
    }

    /**
     * Appends a clause as not-statement.
     *
     * @param Clause $query A query
     *
     * @return self
     */
    public function _not(Clause $query): self
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
        return $this->getFieldSpecification()
            . '(' . implode('' , $this->elements) . ')';
    }
}
