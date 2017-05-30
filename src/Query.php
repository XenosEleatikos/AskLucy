<?php

namespace AskLucy;

use AskLucy\Property\BoostTrait;
use AskLucy\Property\FieldTrait;
use AskLucy\Property\OperatorTrait;

/**
 * A query
 */
class Query implements Clause
{
    use BoostTrait;
    use FieldTrait;
    use OperatorTrait;

    /**
     * Separator between clauses
     *
     * @var string
     */
    const CLAUSE_SEPARATOR = ' ';

    /**
     * A list of clauses
     *
     * @var Clause[]
     */
    private $clauses = [];

    /**
     * Constructs a query.
     *
     * @param string $field Optional name of the field to search in
     */
    public function __construct(string $field = Field::DEFAULT)
    {
        $this->optional();
        $this->field = new Field($field);
    }

    /**
     * Adds a clause.
     *
     * @param Clause $clause A clause
     *
     * @return Query
     */
    public function add(Clause $clause): self
    {
        $this->clauses[] = $clause;

        return $this;
    }

    /**
     * Adds an optional clause.
     *
     * @param Clause $clause A clause
     *
     * @return Query
     */
    public function shouldHave(Clause $clause): self
    {
        $clause->optional();

        return $this->add($clause);
    }

    /**
     * Adds a required clause.
     *
     * @param Clause $clause A clause
     *
     * @return Query
     */
    public function mustHave(Clause $clause): self
    {
        $clause->required();

        return $this->add($clause);
    }

    /**
     * Adds a prohibited clause.
     *
     * @param Clause $clause A clause
     *
     * @return Query
     */
    public function mustNotHave(Clause $clause): self
    {
        $clause->prohibited();

        return $this->add($clause);
    }

    /**
     * Returns the query as string.
     *
     * @return string
     */
    public function __toString(): string
    {
        return $this->operator
            . $this->field
            . $this->getClauses()
            . $this->boost;
    }

    /**
     * @return string
     */
    private function getClauses(): string
    {
        $clauses = implode(self::CLAUSE_SEPARATOR, $this->clauses);

        return (Field::DEFAULT != $this->field && Operator::SYMBOL_OPTIONAL != $this->operator && count($this->clauses) > 1)
            ? '(' .  $clauses . ')'
            : $clauses;
    }
}
