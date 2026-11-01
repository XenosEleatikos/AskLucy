<?php

declare(strict_types=1);

namespace AskLucy\Test\Expression\Clause;

use AskLucy\Exception\InvalidArgumentException;
use AskLucy\Expression\Clause\Clause;
use AskLucy\Lucene;
use PHPUnit\Framework\Attributes\DataProvider;

final class PhraseTest extends ClauseTestCase
{
    public function testRendersPhraseInQuotationMarks(): void
    {
        $this->assertSame('"search phrase"', (string) Lucene::phrase('search phrase'));
    }

    public function testRendersSingleWordInQuotationMarks(): void
    {
        $this->assertSame('"lucene"', (string) Lucene::phrase('lucene'));
    }

    public function testNormalizesWhitespace(): void
    {
        $this->assertSame('"a b c"', (string) Lucene::phrase(" a \t b\nc "));
    }

    public function testThrowsExceptionForPhraseOfWhitespaceOnly(): void
    {
        $this->expectException(InvalidArgumentException::class);

        Lucene::phrase(" \t\n");
    }

    public function testSplitsAtUnicodeWhitespace(): void
    {
        $this->assertSame("\"a b c\"", (string) Lucene::phrase("a\u{3000}b\u{00A0}c"));
    }

    public function testThrowsExceptionForInvalidUtf8(): void
    {
        $this->expectException(InvalidArgumentException::class);

        Lucene::phrase("a \xFF");
    }

    public function testThrowsExceptionForEmptyPhrase(): void
    {
        $this->expectException(InvalidArgumentException::class);

        Lucene::phrase('  ');
    }

    public function testEscapesQuotationMarksAndBackslashes(): void
    {
        $this->assertSame('"say \"hi\" \\\\ there"', (string) Lucene::phrase('say "hi" \\ there'));
    }

    public function testKeepsOtherSpecialCharactersUnescaped(): void
    {
        $this->assertSame('"a+b (c)"', (string) Lucene::phrase('a+b (c)'));
    }

    #[DataProvider('provideProximities')]
    public function testSetProximity(?int $proximity, string $expected): void
    {
        $phrase = Lucene::phrase('search phrase');

        $this->assertSame(
            $phrase,
            null === $proximity ? $phrase->setProximity() : $phrase->setProximity($proximity),
            'Expected a fluent interface.'
        );
        $this->assertSame($expected, (string) $phrase);
    }

    /** @return array<string, array{?int, string}> */
    public static function provideProximities(): array
    {
        return [
            'default argument' => [null, '"search phrase"'],
            'proximity 0'      => [0, '"search phrase"'],
            'proximity 1'      => [1, '"search phrase"~1'],
            'proximity 5'      => [5, '"search phrase"~5'],
        ];
    }

    public function testProximityIsNotRenderedByDefault(): void
    {
        $this->assertStringNotContainsString('~', (string) Lucene::phrase('search phrase'));
    }

    public function testProximityIsNotRenderedForSingleWord(): void
    {
        $this->assertSame('"lucene"', (string) Lucene::phrase('lucene')->setProximity(1));
    }

    public function testLastProximityWins(): void
    {
        $this->assertSame('"a b"~2', (string) Lucene::phrase('a b')->setProximity(1)->setProximity(2));
    }

    protected function getExpectedBody(): string
    {
        return '"a b"';
    }

    protected function getTestClause(?string $constructorArgumentField = null): Clause
    {
        return null === $constructorArgumentField
            ? Lucene::phrase('a b')
            : Lucene::phrase('a b', $constructorArgumentField);
    }
}
