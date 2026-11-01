<?php

declare(strict_types=1);

namespace AskLucy\Test\Expression;

use AskLucy\Expression\Field;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

final class FieldTest extends TestCase
{
    public function testToString(): void
    {
        $this->assertSame('field:', (string) new Field('field'));
    }

    public function testEscapesFieldName(): void
    {
        $this->assertSame('my\ field\:x:', (string) new Field('my field:x'));
    }

    public function testEscapesUnicodeWhitespaceInFieldName(): void
    {
        $this->assertSame("f\\\u{3000}g:", (string) new Field("f\u{3000}g"));
    }

    #[DataProvider('provideDefaultFields')]
    public function testToStringWithDefaultField(Field $field): void
    {
        $this->assertSame('', (string) $field);
    }

    /** @return array<string, array{Field}> */
    public static function provideDefaultFields(): array
    {
        return [
            'without constructor argument'                 => [new Field()],
            'with empty string as constructor argument'    => [new Field('')],
        ];
    }
}
