<?php

namespace LuceneQuery;

use LuceneQuery\Property\OperatorTrait;

/**
 * A query
 */
class Query implements Clause
{
    use OperatorTrait;

    /**
     * Separator between clauses
     *
     * @var string
     */
    const CLAUSE_SEPARATOR = ' ';

    /**
     * Separator between field name and clause
     *
     * @var string
     */
    private const FIELD_SEPARATOR = ':';

    /**
     * The field to search in
     *
     * @var Field
     */
    private $field;

    /**
     * A list of clauses
     *
     * @var Clause[]
     */
    private $clauses = [];

    /**
     * Constructs a query.
     *
     * @param string $field The name of the field
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
            . $this->getFieldSpecification()
            . $this->getClauses();
    }

    /**
     * Returns the field specification.
     *
     * @return string
     */
    private function getFieldSpecification(): string
    {
        return (empty((string) $this->field))
            ? Field::DEFAULT
            : (string) $this->field . self::FIELD_SEPARATOR;
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
