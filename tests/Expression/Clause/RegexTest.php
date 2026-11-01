<?php

declare(strict_types=1);

namespace AskLucy\Test\Expression\Clause;

use AskLucy\Exception\InvalidArgumentException;
use AskLucy\Expression\Clause\Clause;
use AskLucy\Lucene;

final class RegexTest extends ClauseTestCase
{
    public function testRendersPatternBetweenSlashes(): void
    {
        $this->assertSame('/[mb]oat/', (string) Lucene::regex('[mb]oat'));
    }

    public function testEscapesSlashes(): void
    {
        $this->assertSame('/a\/b/', (string) Lucene::regex('a/b'));
    }

    public function testKeepsEscapedCharactersUntouched(): void
    {
        $this->assertSame('/a\/b\.c\\\\\//', (string) Lucene::regex('a\/b\.c\\\\/'));
    }

    public function testKeepsEscapedCharacterAtEndOfPatternUntouched(): void
    {
        $this->assertSame('/a\\//', (string) Lucene::regex('a\\/'));
    }

    public function testThrowsExceptionForPatternEndingWithBackslash(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('must not end with a backslash. Use the character class [\\\\] to match one.');

        Lucene::regex('a\\');
    }

    public function testThrowsExceptionForPatternEndingWithEscapedBackslash(): void
    {
        $this->expectException(InvalidArgumentException::class);

        Lucene::regex('a\\\\');
    }

    public function testMatchesLiteralBackslashWithCharacterClass(): void
    {
        $this->assertSame('/a[\\\\]/', (string) Lucene::regex('a[\\\\]'));
    }

    public function testThrowsExceptionForEmptyPattern(): void
    {
        $this->expectException(InvalidArgumentException::class);

        Lucene::regex('');
    }

    protected function getExpectedBody(): string
    {
        return '/a/';
    }

    protected function getTestClause(?string $constructorArgumentField = null): Clause
    {
        // The pattern "a" makes the generic field, operator and boost assertions applicable.
        return null === $constructorArgumentField
            ? Lucene::regex('a')
            : Lucene::regex('a', $constructorArgumentField);
    }
}
