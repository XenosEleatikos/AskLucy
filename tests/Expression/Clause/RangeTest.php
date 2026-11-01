<?php

declare(strict_types=1);

namespace AskLucy\Test\Expression\Clause;

use AskLucy\Exception\InvalidArgumentException;
use AskLucy\Expression\Clause\Clause;
use AskLucy\Lucene;
use PHPUnit\Framework\Attributes\DataProvider;

final class RangeTest extends ClauseTestCase
{
    public function testIsInclusiveByDefault(): void
    {
        $this->assertSame('[a TO b]', (string) Lucene::range('a', 'b'));
    }

    public function testExclusive(): void
    {
        $range = Lucene::range('a', 'b');

        $this->assertSame($range, $range->exclusive(), 'Expected a fluent interface.');
        $this->assertSame('{a TO b}', (string) $range);
    }

    public function testInclusiveOverwritesExclusive(): void
    {
        $range = Lucene::range('a', 'b')->exclusive();

        $this->assertSame($range, $range->inclusive(), 'Expected a fluent interface.');
        $this->assertSame('[a TO b]', (string) $range);
    }

    public function testMixedBounds(): void
    {
        $this->assertSame('{a TO b]', (string) Lucene::range('a', 'b')->exclusiveFrom());
        $this->assertSame('[a TO b}', (string) Lucene::range('a', 'b')->exclusiveTo());
        $this->assertSame('[a TO b]', (string) Lucene::range('a', 'b')->exclusive()->inclusiveFrom()->inclusiveTo());
    }

    public function testMixedBoundMethodsReturnTheRange(): void
    {
        $range = Lucene::range('a', 'b');

        $this->assertSame($range, $range->exclusiveFrom());
        $this->assertSame($range, $range->exclusiveTo());
        $this->assertSame($range, $range->inclusiveFrom());
        $this->assertSame($range, $range->inclusiveTo());
    }

    public function testOpenBounds(): void
    {
        $this->assertSame('[* TO 5]', (string) Lucene::range(null, '5'));
        $this->assertSame('[5 TO *]', (string) Lucene::range('5', null));
        $this->assertSame('{* TO *}', (string) Lucene::range(null, null)->exclusive());
    }

    public function testBoundsAreEscaped(): void
    {
        $this->assertSame('[2020\-01\-01 TO \*]', (string) Lucene::range('2020-01-01', '*'));
    }

    public function testBoundsWithWhitespaceAreQuoted(): void
    {
        $this->assertSame('["Anna Maria" TO Doro]', (string) Lucene::range('Anna Maria', 'Doro'));
    }

    public function testBoundsWithUnicodeWhitespaceAreQuoted(): void
    {
        $this->assertSame("[\"a\u{3000}b\" TO c]", (string) Lucene::range("a\u{3000}b", 'c'));
    }

    public function testBoundsWithClosingBracketsAreQuoted(): void
    {
        $this->assertSame('["a]b" TO "c}d"]', (string) Lucene::range('a]b', 'c}d'));
    }

    public function testQuotedBoundsEscapeQuotationMarksAndBackslashes(): void
    {
        $this->assertSame('["a\\"]" TO c]', (string) Lucene::range('a"]', 'c'));
    }

    #[DataProvider('provideBoundsEndingWithBackslash')]
    public function testThrowsExceptionForQuotedBoundEndingWithBackslash(string $bound): void
    {
        $this->expectException(InvalidArgumentException::class);

        Lucene::range('a', $bound);
    }

    /** @return array<string, array{string}> */
    public static function provideBoundsEndingWithBackslash(): array
    {
        return [
            'with whitespace'      => ['a b\\'],
            'with closing bracket' => ['a]\\'],
            'with closing brace'   => ['a}\\'],
        ];
    }

    public function testAcceptsUnquotedBoundEndingWithBackslash(): void
    {
        $this->assertSame('[a\\\\ TO b]', (string) Lucene::range('a\\', 'b'));
    }

    public function testTrimsBounds(): void
    {
        $this->assertSame('[a TO b]', (string) Lucene::range(' a ', "\tb\n"));
        $this->assertSame('[a TO b]', (string) Lucene::range("\0a\u{3000}", "\fb"));
    }

    public function testThrowsExceptionForEmptyBound(): void
    {
        $this->expectException(InvalidArgumentException::class);

        Lucene::range('', 'b');
    }

    public function testRendersFieldBeforeRange(): void
    {
        $this->assertSame('name:[a TO b]', (string) Lucene::range('a', 'b', 'name'));
    }

    protected function getExpectedBody(): string
    {
        return '[a TO b]';
    }

    protected function getTestClause(?string $constructorArgumentField = null): Clause
    {
        return null === $constructorArgumentField
            ? Lucene::range('a', 'b')
            : Lucene::range('a', 'b', $constructorArgumentField);
    }
}
