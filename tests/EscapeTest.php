<?php

declare(strict_types=1);

namespace AskLucy\Test;

use AskLucy\Escape;
use AskLucy\Exception\InvalidArgumentException;
use AskLucy\Lucene;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

final class EscapeTest extends TestCase
{
    #[DataProvider('provideSpecialCharacters')]
    public function testTermEscapesEverySpecialCharacter(string $character): void
    {
        $this->assertSame('\\' . $character, Escape::term($character));
    }

    /** @return array<string, array{string}> */
    public static function provideSpecialCharacters(): array
    {
        $cases = [];

        foreach (str_split('+-!(){}[]^"~*?:\\/&|') as $character) {
            $cases[$character] = [$character];
        }

        return $cases;
    }

    public function testTermLeavesOrdinaryCharactersUntouched(): void
    {
        $this->assertSame('Lucene_2.0äö', Escape::term('Lucene_2.0äö'));
    }

    public function testTermEscapesReservedWords(): void
    {
        $this->assertSame('\AND', Escape::term('AND'));
        $this->assertSame('\OR', Escape::term('OR'));
        $this->assertSame('\NOT', Escape::term('NOT'));
        $this->assertSame('\TO', Escape::term('TO'));
        $this->assertSame('and', Escape::term('and'));
        $this->assertSame('ANDROID', Escape::term('ANDROID'));
    }

    public function testFieldEscapesWhitespace(): void
    {
        $this->assertSame('a\ b\:c', Escape::field('a b:c'));
    }

    public function testPhraseEscapesOnlyQuotationMarksAndBackslashes(): void
    {
        $this->assertSame('a\"b\\\\c+d', Escape::phrase('a"b\\c+d'));
    }

    public function testWildcardPreservesWildcards(): void
    {
        $this->assertSame('a*b?\(', Escape::wildcard('a*b?('));
    }

    public function testRegexKeepsTrailingBackslashUntouched(): void
    {
        $this->assertSame('a\\', Escape::regex('a\\'));
    }

    public function testWildcardEscapesReservedWords(): void
    {
        $this->assertSame('\AND', Escape::wildcard('AND'));
        $this->assertSame('\NOT', Escape::wildcard('NOT'));
        $this->assertSame('and*', Escape::wildcard('and*'));
    }

    public function testLiteralEscapesWhitespaceSoThatTheResultIsASingleToken(): void
    {
        $this->assertSame('a\ AND\ b', Escape::literal('a AND b'));
        $this->assertSame("a\\\t\\\nb", Escape::literal("a\t\nb"));
        $this->assertSame("a\\\u{3000}b", Escape::literal("a\u{3000}b"));
    }

    public function testLiteralThrowsExceptionForInvalidUtf8(): void
    {
        $this->expectException(InvalidArgumentException::class);

        Escape::literal("a\xFFb");
    }

    #[DataProvider('provideWhitespace')]
    public function testContainsWhitespace(string $string, bool $expected): void
    {
        $this->assertSame($expected, Escape::containsWhitespace($string));
    }

    /** @return array<string, array{string, bool}> */
    public static function provideWhitespace(): array
    {
        return [
            'no whitespace'     => ['abc', false],
            'space'             => ['a b', true],
            'tab'               => ["a\tb", true],
            'ideographic space' => ["a\u{3000}b", true],
            'no-break space'    => ["a\u{00A0}b", true],
            'umlauts'           => ['äöü', false],
            'NUL byte'          => ["a\0b", true],
        ];
    }

    #[DataProvider('provideStringsToTrim')]
    public function testTrim(string $string, string $expected): void
    {
        $this->assertSame($expected, Escape::trim($string));
    }

    /** @return array<string, array{string, string}> */
    public static function provideStringsToTrim(): array
    {
        return [
            'nothing to trim'          => ['a b', 'a b'],
            'spaces'                   => ['  a b  ', 'a b'],
            'new line at the end'      => ["a\n", 'a'],
            'form feed'                => ["\fa\f", 'a'],
            'NUL bytes'                => ["\0a\0", 'a'],
            'ideographic space'        => ["\u{3000}a\u{3000}", 'a'],
            'only whitespace'          => [" \t\n", ''],
            'inner NUL byte is kept'   => ["a\0b", "a\0b"],
        ];
    }

    public function testTrimThrowsExceptionForInvalidUtf8(): void
    {
        $this->expectException(InvalidArgumentException::class);

        Escape::trim("a\xFF");
    }

    public function testWordsSplitsAtWhitespace(): void
    {
        $this->assertSame(['a', 'b', 'c', 'd'], Escape::words(" a \t b\u{3000}c\0d\n"));
        $this->assertSame([], Escape::words(" \n"));
    }

    public function testWordsThrowsExceptionForInvalidUtf8(): void
    {
        $this->expectException(InvalidArgumentException::class);

        Escape::words("a \xFF");
    }

    public function testContainsWhitespaceThrowsExceptionForInvalidUtf8(): void
    {
        $this->expectException(InvalidArgumentException::class);

        Escape::containsWhitespace("a\xFF");
    }

    public function testLuceneEscapeNeutralizesEmbeddedOperators(): void
    {
        $this->assertSame('x\ OR\ y', Lucene::escape('x OR y'));
    }

    public function testLuceneEscapeEscapesTerms(): void
    {
        $this->assertSame('1\+1=2', Lucene::escape('1+1=2'));
    }
}
