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
        parent::__construct();

        $this->elements = [$query];
    }

    /**
     * Appends a clause with optional matching.
     *
     * @param Clause $clause A clause
     *
     * @return Query
     */
    public function addOptionalClause(Clause $clause): self
    {
        $this->addSubQuery(
            new Operator(Operator::SYMBOL_OPTIONAL),
            $clause
        );

        return $this;
    }

    /**
     * Appends a clause with required matching.
     *
     * @param Clause $clause A clause
     *
     * @return Query
     */
    public function addRequiredClause(Clause $clause): self
    {
        $this->addSubQuery(
            new Operator(Operator::SYMBOL_REQUIRED),
            $clause
        );

        return $this;
    }

    /**
     * Appends a clause with prohibited matching.
     *
     * @param Clause $clause A clause
     *
     * @return Query
     */
    public function addProhibitedClause(Clause $clause): self
    {
        $this->addSubQuery(
            new Operator(Operator::SYMBOL_PROHIBITED),
            $clause
        );

        return $this;
    }

    /**
     * Appends a clause as and-statement.
     *
     * @param Clause $clause A clause
     *
     * @return self
     */
    public function _and(Clause $clause): self
    {
        $this->addSubQuery(
            new Operator(Operator::SYMBOL_AND),
            $clause
        );

        return $this;
    }

    /**
     * Appends a clause as or-statement.
     *
     * @param Clause $clause A clause
     *
     * @return self
     */
    public function _or(Clause $clause): self
    {
        $this->addSubQuery(
            new Operator(Operator::SYMBOL_OR),
            $clause
        );

        return $this;
    }

    /**
     * Appends a clause as not-statement.
     *
     * @param Clause $clause A clause
     *
     * @return self
     */
    public function _not(Clause $clause): self
    {
        $this->addSubQuery(
            new Operator(Operator::SYMBOL_NOT),
            $clause
        );

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

    /**
     * Adds a sub query.
     *
     * @param Operator $operator An operator
     * @param Clause   $clause   A clause
     *
     * @return void
     */
    private function addSubQuery(Operator $operator, Clause $clause): void
    {
        $this->elements[] = $operator;
        $this->elements[] = $clause;
    }
}
