<?php

namespace LuceneQuery\Test\Property;

use LuceneQuery\Clause;

trait OperatorTraitTest
{
    /**
     * Returns a clause for testing.
     *
     * @return Clause
     */
    abstract protected function getTestClause(?string $constructorArgumentField = null): Clause;

    /**
     * Tests, if optional() adds no operator symbol.
     *
     * @return void
     */
    public function testOptional(): void
    {
        $query = $this->getTestClause();
        $query->optional();

        $this->assertSame(
            'a',
            (string) $query
        );
    }

    /**
     * Tests, if optional() overwrites an operator set before.
     *
     * @return void
     */
    public function testOptionalOverwritesOperator(): void
    {
        $query = $this->getTestClause();
        $query->required();
        $query->optional();

        $this->assertSame(
            'a',
            (string) $query
        );
    }

    /**
     * Tests, if required() adds the operator symbol "+" to require a clause.
     *
     * @return void
     */
    public function testRequired(): void
    {
        $query = $this->getTestClause();
        $query->required();

        $this->assertSame(
            '+a',
            (string) $query
        );
    }

    /**
     * Tests, if required() overwrites an operator set before.
     *
     * @return void
     */
    public function testRequiredOverwritesOperator(): void
    {
        $query = $this->getTestClause();
        $query->optional();
        $query->required();

        $this->assertSame(
            '+a',
            (string) $query
        );
    }

    /**
     * Tests, if prohibited() adds the operator symbol "-" to prohibit a clause.
     *
     * @return void
     */
    public function testProhibited(): void
    {
        $query = $query = $this->getTestClause();
        $query->prohibited();

        $this->assertSame(
            '-a',
            (string) $query
        );
    }

    /**
     * Tests, if prohibited() overwrites an operator set before.
     *
     * @return void
     */
    public function testProhibitedOverwritesOperator(): void
    {
        $query = $query = $this->getTestClause();
        $query->required();
        $query->prohibited();

        $this->assertSame(
            '-a',
            (string) $query
        );
    }
}