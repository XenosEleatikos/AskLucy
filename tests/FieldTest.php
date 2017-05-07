<?php
namespace LuceneQuery\Test;

use LuceneQuery\Field;
use PHPUnit\Framework\TestCase;

/**
 * Unit tests for the field.
 *
 * @see Field
 */
class FieldTest extends TestCase
{
    /**
     * Tests, if Field::__toString() returns the field name given as constructor argument.
     *
     * @return void
     */
    public function test__toString(): void
    {
        $field = new Field('field');

        $this->assertSame(
            'field',
            (string) $field,
            'Expected field name to be "field".'
        );
    }

    /**
     * Tests, if Field::__toString() returns an empty string, if no field name was given to the constructor.
     *
     * @return void
     */
    public function test__toStringWithoutFieldName(): void
    {
        $field = new Field;

        $this->assertSame(
            '',
            (string) $field,
            'Expected field name to be an empty string.'
        );
    }

    /**
     * Tests, if Field::__toString() returns an empty string, if an empty string was given to the constructor.
     *
     * @return void
     */
    public function test__toStringWithEmptyFieldName(): void
    {
        $field = new Field('');

        $this->assertSame(
            '',
            (string) $field,
            'Expected field name to be an empty string.'
        );
    }
}
