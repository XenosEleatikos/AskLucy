<?php

declare(strict_types=1);

namespace AskLucy\Test\Expression\Clause;

use AskLucy\Exception\InvalidArgumentException;
use AskLucy\Expression\Clause\Clause;
use AskLucy\Lucene;
use PHPUnit\Framework\Attributes\DataProvider;

final class TermTest extends ClauseTestCase
{
    public function testRendersTerm(): void
    {
        $this->assertSame('term', (string) Lucene::term('term'));
    }

    #[DataProvider('providePhrases')]
    public function testThrowsExceptionForWhitespaceInTerm(string $phrase): void
    {
        $this->expectException(InvalidArgumentException::class);

        Lucene::term($phrase);
    }

    /** @return array<string, array{string}> */
    public static function providePhrases(): array
    {
        return [
            'space'   => ['search phrase'],
            'tab'     => ["search\tphrase"],
            'newline' => ["search\nphrase"],
            'ideographic space' => ["search\u{3000}phrase"],
            'no-break space'    => ["search\u{00A0}phrase"],
            'NUL byte'          => ["search\0phrase"],
            'form feed'         => ["search\fphrase"],
        ];
    }

    public function testThrowsExceptionForInvalidUtf8(): void
    {
        $this->expectException(InvalidArgumentException::class);

        Lucene::term("a\xFF");
    }

    public function testThrowsExceptionForEmptyTerm(): void
    {
        $this->expectException(InvalidArgumentException::class);

        Lucene::term('  ');
    }

    #[DataProvider('provideStringsWithSurroundingWhitespace')]
    public function testTrimsSearchString(string $searchString): void
    {
        $this->assertSame('term', (string) Lucene::term($searchString));
    }

    /** @return array<string, array{string}> */
    public static function provideStringsWithSurroundingWhitespace(): array
    {
        $cases = [];

        foreach (['ideographic space' => "\u{3000}", 'form feed' => "\f", 'space' => ' ', 'tab' => "\t", 'new line' => "\n", 'carriage return' => "\r", 'NUL byte' => "\0", 'vertical tab' => "\x0B"] as $name => $character) {
            $cases['Prepending ' . $name]             = [$character . 'term'];
            $cases['Appending ' . $name]              = ['term' . $character];
            $cases['Prepending and appending ' . $name] = [$character . 'term' . $character];
        }

        return $cases;
    }

    #[DataProvider('provideFuzzifications')]
    public function testFuzzify(int $distance, string $expected): void
    {
        $term = Lucene::term('term');

        $this->assertSame($term, $term->fuzzify($distance), 'Expected a fluent interface.');
        $this->assertSame($expected, (string) $term);
    }

    /** @return array<string, array{int, string}> */
    public static function provideFuzzifications(): array
    {
        return [
            'negative distance disables fuzziness' => [-1, 'term'],
            'distance 0'                           => [0, 'term'],
            'distance 1'                           => [1, 'term~1'],
            'distance 2'                           => [2, 'term~'],
            'too big distance is limited to 2'     => [3, 'term~'],
        ];
    }

    public function testFuzzifyDefaultsToDistance2(): void
    {
        $this->assertSame('term~', (string) Lucene::term('term')->fuzzify());
    }

    public function testFuzzifyMethodsReturnTheTermAndOverwriteEachOther(): void
    {
        $term = Lucene::term('term');

        $this->assertSame($term, $term->fuzzify0());
        $this->assertSame($term, $term->fuzzify1());
        $this->assertSame('term~1', (string) $term);
        $this->assertSame($term, $term->fuzzify2());
        $this->assertSame('term~', (string) $term);
        $this->assertSame('term', (string) $term->fuzzify0());
    }

    #[DataProvider('provideSpecialCharacters')]
    public function testEscapesSpecialCharacters(string $searchString, string $expected): void
    {
        $this->assertSame($expected, (string) Lucene::term($searchString));
    }

    /** @return array<string, array{string, string}> */
    public static function provideSpecialCharacters(): array
    {
        return [
            'plus'         => ['a+b', 'a\+b'],
            'colon'        => ['a:b', 'a\:b'],
            'and operator' => ['AND', '\AND'],
            'not operator' => ['NOT', '\NOT'],
            'double ampersand' => ['a&&b', 'a\&\&b'],
            'wildcards'    => ['te*t?', 'te\*t\?'],
        ];
    }

    protected function getExpectedBody(): string
    {
        return 'a';
    }

    protected function getTestClause(?string $constructorArgumentField = null): Clause
    {
        return null === $constructorArgumentField
            ? Lucene::term('a')
            : Lucene::term('a', $constructorArgumentField);
    }
}
