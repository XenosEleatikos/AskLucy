<?php

namespace LuceneQuery\Test\Property;

use LuceneQuery\Clause;

trait FieldTraitTest
{
    /**
     * Returns a clause for testing.
     *
     * @return Clause
     */
    abstract protected function getTestClause(?string $constructorArgumentField = null): Clause;

    /**
     * Tests, if __toString() renders the field specification set by setField().
     *
     * @return void
     */
    public function test__toStringRendersFieldSpecificationSetBySetField(): void
    {
        $query = $this->getTestClause();
        $query->setField('field');

        $this->assertRegExp(
            '/(field:).?a/',
            (string) $query,
            'Expected setField() to prepend the field specification "field:" to the query.'
        );
    }

    /**
     * Tests, if __toString() renders the field specification set by __construct().
     *
     * @return void
     */
    public function test__toStringRendersFieldSpecificationSetBy__construct(): void
    {
        $query = $this->getTestClause('field');

        $this->assertRegExp(
            '/(field:).?a/',
            (string) $query,
            'Expected __construct() to prepend the field specification "field:" to the query.'
        );
    }

    /**
     * Tests, if __toString() doesn't render the field separator, when the clause was instantiated without constructor
     * argument and setField() was not used.
     *
     * @return void
     */
    public function test__toStringDoesNotRendersFieldSeparatorForQueryInstantiatedWithoutConstructorArgument(): void
    {
        $query = $this->getTestClause();

        $this->assertFalse(
            strstr($query, ':'),
            'Expected that __toString() doesn\'t render the field separator ":", if the clause was instantiated without constructor argument and setField() was never called.'
        );
    }

    /**
     * Tests, if __toString() doesn't render any field specification, when the query was instantiated with an empty
     * string as constructor argument and setField() was never used.
     *
     * @return void
     */
    public function test__toStringDoesNotRendersFieldSpecificationForQueryInstantiatedWithEmptyStringAsConstructorArgument(): void
    {
        $query = $this->getTestClause('');

        $this->assertFalse(
            strstr($query, ':'),
            'Expected that __toString() doesn\'t render the field separator ":", if the clause was instantiated with an empty string as constructor argument.'
        );
    }

    /**
     * Tests, if setField() overwrites the constructor argument and __toString() renders the last set value.
     *
     * @return void
     */
    public function testSetFieldOverwritesConstructorArgument(): void
    {
        $query = $this->getTestClause('field');
        $query->setField('otherField');

        $this->assertRegExp(
            '/(otherField:).?a/',
            (string) $query,
            'Expected field specification (otherField:) prepended to the query, because "otherField" was last set by setField().'
        );
    }

    /**
     * Tests, if setField() overwrites a value set by setField() before and __toString() renders the last set value.
     *
     * @return void
     */
    public function testSetFieldOverwritesFieldSetBefore(): void
    {
        $query = $this->getTestClause();
        $query->setField('field');
        $query->setField('otherField');

        $this->assertRegExp(
            '/(otherField:).?a/',
            (string) $query,
            'Expected field specification (otherField:) prepended to the query, because "otherField" was last set by setField().'
        );
    }

    /**
     * Tests, if setField() unsets a field set by setField() before, if no argument is given.
     *
     * @return void
     */
    public function testSetFieldUnsetsFieldSetBeforeIfNotArgumentIsGiven(): void
    {
        $query = $this->getTestClause();
        $query->setField('field');
        $query->setField();

        $this->assertFalse(
            strstr($query, 'field'),
            'Expected no field specification prepended, because setField() was last called without argument.'
        );
    }
}
