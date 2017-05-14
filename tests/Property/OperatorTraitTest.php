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
     * Tests, if optional() doesn't modify the clause.
     *
     * @return void
     */
    public function testOptional(): void
    {
        $clause = $this->getTestClause();
        $originalClause = (string) $clause;

        $clause->optional();

        $this->assertSame(
            $originalClause,
            (string) $clause,
            'Expected the clause to be the same after calling optional() as before.'
        );
    }

    /**
     * Tests, if optional() overwrites an operator set before.
     *
     * @return void
     */
    public function testOptionalOverwritesOperator(): void
    {
        $clause = $this->getTestClause();
        $originalClause = (string) $clause;

        $clause->required();
        $clause->prohibited();
        $clause->optional();

        $this->assertSame(
            $originalClause,
            (string) $clause,
            'Expected optional() to overwrite operators set before.'
        );
    }

    /**
     * Tests, if required() prepends the operator symbol "+" to the clause.
     *
     * @return void
     */
    public function testRequired(): void
    {
        $clause = $this->getTestClause();
        $clause->required();

        $this->assertRegExp(
            '/\+.?a/',
            (string) $clause,
            'Expected required() to prepend the operator symbol "+" to the clause.'
        );
    }

    /**
     * Tests, if required() overwrites an operator set before.
     *
     * @return void
     */
    public function testRequiredOverwritesOperator(): void
    {
        $clause = $this->getTestClause();
        $clause->optional();
        $clause->prohibited();
        $clause->required();

        $this->assertRegExp(
            '/\+.?a/',
            (string) $clause,
            'Expected required() to overwrite operators set before.'
        );
    }

    /**
     * Tests, if prohibited() prepends the operator symbol "-" to the clause.
     *
     * @return void
     */
    public function testProhibited(): void
    {
        $clause = $this->getTestClause();
        $clause->prohibited();

        $this->assertRegExp(
            '/\-.?a/',
            (string) $clause,
            'Expected prohibited() to prepend the operator symbol "-" to the clause.'
        );
    }

    /**
     * Tests, if prohibited() overwrites an operator set before.
     *
     * @return void
     */
    public function testProhibitedOverwritesOperator(): void
    {
        $clause = $this->getTestClause();
        $clause->optional();
        $clause->required();
        $clause->prohibited();

        $this->assertRegExp(
            '/\-.?a/',
            (string) $clause,
            'Expected prohibited() to overwrite operators set before.'
        );
    }
}