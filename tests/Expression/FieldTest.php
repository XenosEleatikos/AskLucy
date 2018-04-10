<?php
namespace AskLucy\Test\Expression;

use AskLucy\Expression\Field;
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
            'field:',
            (string) $field,
            'Expected field name to be "field:".'
        );
    }

    /**
     * Tests, if Field::__toString() returns an empty string for the default field.
     *
     * @param Field $field An empty field
     *
     * @dataProvider dataProviderTest__toStringWithDefaultField
     *
     * @return void
     */
    public function test__toStringWithDefaultField(Field $field): void
    {
        $this->assertEmpty(
            (string) $field,
            'Expected field name to be an empty string.'
        );
    }

    /**
     * Returns empty fields as test data for test__toStringWithDefaultField().
     *
     * @return array
     */
    public function dataProviderTest__toStringWithDefaultField(): array
    {
        return [
            'Default field instantiated without constructor argument'              => [new Field],
            'Default field instantiated with empty string as constructor argument' => [new Field('')]
        ];
    }
}
