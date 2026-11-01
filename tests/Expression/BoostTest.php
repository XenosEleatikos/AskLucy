<?php

declare(strict_types=1);

namespace AskLucy\Test\Expression;

use AskLucy\Exception\InvalidArgumentException;
use AskLucy\Expression\Boost;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

final class BoostTest extends TestCase
{
    #[DataProvider('provideInvalidValues')]
    public function testConstructThrowsExceptionForInvalidValue(float $value): void
    {
        $this->expectException(InvalidArgumentException::class);

        new Boost($value);
    }

    /** @return array<string, array{float}> */
    public static function provideInvalidValues(): array
    {
        return [
            'negative'  => [-1.0],
            'zero'      => [0.0],
            'infinite'  => [INF],
            'rounds to zero' => [1.0E-11],
            'not a number' => [NAN],
        ];
    }

    #[DataProvider('provideValues')]
    public function testToString(float $value, string $expected): void
    {
        $this->assertSame($expected, (string) new Boost($value));
    }

    /** @return array<string, array{float, string}> */
    public static function provideValues(): array
    {
        return [
            'default value is omitted'         => [1.0, ''],
            'integer value'                    => [2.0, '^2'],
            'smallest renderable value'        => [0.0000000001, '^0.0000000001'],
            'decimal value'                    => [2.5, '^2.5'],
            'trailing zeros are removed'       => [2.10, '^2.1'],
            'small value without exponent'     => [0.0000001, '^0.0000001'],
        ];
    }

    public function testDefaultConstructorArgumentRendersNothing(): void
    {
        $this->assertSame('', (string) new Boost());
    }
}
