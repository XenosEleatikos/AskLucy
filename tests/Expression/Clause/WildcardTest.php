<?php

declare(strict_types=1);

namespace AskLucy\Test\Expression\Clause;

use AskLucy\Exception\InvalidArgumentException;
use AskLucy\Expression\Clause\Clause;
use AskLucy\Lucene;

final class WildcardTest extends ClauseTestCase
{
    public function testRendersWildcards(): void
    {
        $this->assertSame('te?t*', (string) Lucene::wildcard('te?t*'));
        $this->assertSame('*test', (string) Lucene::wildcard('*test'));
    }

    public function testEscapesOtherSpecialCharacters(): void
    {
        $this->assertSame('a\-b*\:', (string) Lucene::wildcard('a-b*:'));
    }

    public function testEscapesReservedWords(): void
    {
        $this->assertSame('\AND', (string) Lucene::wildcard('AND'));
        $this->assertSame('a \OR', (string) Lucene::query()->add(Lucene::term('a'))->add(Lucene::wildcard('OR')));
    }

    public function testThrowsExceptionForUnicodeWhitespace(): void
    {
        $this->expectException(InvalidArgumentException::class);

        Lucene::wildcard("a\u{3000}b*");
    }

    public function testTrimsPattern(): void
    {
        $this->assertSame('a*', (string) Lucene::wildcard(' a* '));
        $this->assertSame('a*', (string) Lucene::wildcard("\0a*\u{3000}"));
    }

    public function testThrowsExceptionForEmptyPattern(): void
    {
        $this->expectException(InvalidArgumentException::class);

        Lucene::wildcard('');
    }

    public function testThrowsExceptionForWhitespace(): void
    {
        $this->expectException(InvalidArgumentException::class);

        Lucene::wildcard('a b*');
    }

    protected function getExpectedBody(): string
    {
        return 'a*';
    }

    protected function getTestClause(?string $constructorArgumentField = null): Clause
    {
        return null === $constructorArgumentField
            ? Lucene::wildcard('a*')
            : Lucene::wildcard('a*', $constructorArgumentField);
    }
}
