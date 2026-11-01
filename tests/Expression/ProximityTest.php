<?php

declare(strict_types=1);

namespace AskLucy\Test\Expression;

use AskLucy\Exception\InvalidArgumentException;
use AskLucy\Expression\Proximity;
use PHPUnit\Framework\TestCase;

final class ProximityTest extends TestCase
{
    public function testConstructThrowsExceptionForNegativeValue(): void
    {
        $this->expectException(InvalidArgumentException::class);

        new Proximity(-1);
    }

    public function testToString(): void
    {
        $this->assertSame('~3', (string) new Proximity(3));
        $this->assertSame('', (string) new Proximity(0));
        $this->assertSame('', (string) new Proximity());
    }
}
