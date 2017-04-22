<?php
namespace LuceneQuery\Test;

use LuceneQuery\Field;
use LuceneQuery\Term;
use PHPUnit\Framework\TestCase;

/**
 * Unit tests for the term.
 *
 * @see Term
 */
class TermTest extends TestCase
{
    /**
     * Tests, if Term::__construct() strips whitespace (or similar characters) from the beginning and end of a single
     * term.
     *
     * @dataProvider dataProviderTest__ConstructTrimsSearchTerm
     *
     * @return void
     */
    public function test__constructTrimsSingleTerm($searchString): void
    {
        $term = new Term($searchString);

        $this->assertSame(
            'term',
            (string) $term,
            'Expected leading and ending whitespace characters to be stripped from the term.'
        );
    }

    /**
     * Tests, if Term::__construct() strips whitespace (or similar characters) from the beginning and end of a phrase
     * of several words.
     *
     * @dataProvider dataProviderTest__ConstructTrimsSearchPhrase
     *
     * @return void
     */
    public function test__constructTrimsPhrase($searchString): void
    {
        $term = new Term($searchString);

        $this->assertSame(
            '"a search phrase"',
            (string) $term,
            'Expected leading and ending whitespace characters to be stripped from the phrase.'
        );
    }

    /**
     * Tests, if setField() specifies a field for a single term.
     *
     * @return void
     */
    public function testSetFieldToSingleTerm(): void
    {
        $term   = new Term('term');
        $result = $term->setField(new Field('field'));

        $this->assertSame(
            $term,
            $result,
            'Expected setField() to return the term itself for a fluent interface.'
        );

        $this->assertSame(
            'field:term',
            (string) $term
        );
    }

    /**
     * Tests, if setField() specifies a field for a phrase of several words.
     *
     * @return void
     */
    public function testSetFieldToPhrase(): void
    {
        $term   = new Term('a search phrase');
        $result = $term->setField(new Field('field'));

        $this->assertSame(
            $term,
            $result,
            'Expected setField() to return the term itself for a fluent interface.'
        );
        $this->assertSame(
            'field:"a search phrase"',
            (string) $term
        );
    }

    /**
     * Tests, if fuzzify() appends the fuzzy operator correctly and returns the term itself for a fluent interface.
     *
     * @param string $searchString   The search string to be fuzzified
     * @param int    $distance       The distance parameter, given to fuzzifySingleWordTerm()
     * @param string $expectedResult The expected term
     *
     * @dataProvider dataProviderTestFuzzifyTerm
     *
     * @return void
     */
    public function testFuzzifyTerm(string $searchString, int $distance, string $expectedResult): void
    {
        $term   = new Term($searchString);
        $result = $term->fuzzify($distance);

        $this->assertSame(
            $term,
            $result,
            'Expected fuzzify() to return the term itself for a fluent interface.'
        );

        $this->assertSame(
            $expectedResult,
            (string) $term,
            'Expected term to be "'
            . $expectedResult
            . '", if Damerau-Levenshtein Distance '
            . $distance
            . ' given as parameter.'
        );
    }

    /**
     * Tests, if fuzzify() appends the fuzzy operator without distance operator, if no parameter given. Also tests, if
     * in that case the term itself is returned for a fluent interface.
     *
     * @return void
     */
    public function testFuzzifyTermWithDefaultParameter()
    {
        $term = new Term('term');
        $result = $term->fuzzify();

        $this->assertSame(
            $term,
            $result,
            'Expected fuzzify() to return the term itself for a fluent interface.'
        );

        $this->assertSame(
            'term~',
            (string) $term,
            'Expected fuzzy term to be "term~", if no Damerau-Levenshtein Distance given as parameter.'
        );
    }

    /**
     * Tests, if Term::__toString() returns the term given as constructor argument.
     *
     * @return void
     */
    public function test__toString(): void
    {
        $term = new Term('term');

        $this->assertSame(
            'term',
            (string) $term,
            'Expected term to be "term".'
        );
    }

    /**
     * Tests, if Term::__toString() returns an empty string, if an empty string was given to the constructor.
     *
     * @return void
     */
    public function test__toStringWithEmptyTermName(): void
    {
        $term = new Term('');

        $this->assertSame(
            '',
            (string) $term,
            'Expected term to be an empty string.'
        );
    }

    /**
     * Tests, if phrases containing several words being quoted.
     *
     * @return void
     */
    public function testAddQuotes(): void
    {
        $term = new Term('a search phrase');

        $this->assertSame(
            '"a search phrase"',
            (string) $term,
            'Expected phrases containing several words being quoted.'
        );
    }

    /**
     * Data provider for test__constructTrimsSearchTerm().
     *
     * @return array
     */
    public function dataProviderTest__constructTrimsSearchTerm(): array
    {
        return $this->getWhiteSpacedTerms('term');
    }

    /**
     * Data provider for test__constructTrimsSearchPhrase().
     *
     * @return array
     */
    public function dataProviderTest__constructTrimsSearchPhrase(): array
    {
        return $this->getWhiteSpacedTerms('a search phrase');
    }

    /**
     * Data provider for testFuzzifyTerm().
     *
     * @return array
     */
    public function dataProviderTestFuzzifyTerm(): array
    {
        return [
            'Single word term with distance 0' => ['term', 0, 'term'],
            'Single word term with distance 1' => ['term', 1, 'term~1'],
            'Single word term with distance 2' => ['term', 2, 'term~'],
            'Phrase with distance 0'           => ['a search string', 0, '"a search string"'],
            'Phrase with distance 1'           => ['a search string', 1, '"a~1 search~1 string~1"'],
            'Phrase with distance 2'           => ['a search string', 2, '"a~ search~ string~"'],
        ];
    }

    /**
     * Creates a list of strings by appending and prepending white space characters to a given string.
     *
     * @param string $searchString A search string
     *
     * @return array
     */
    private function getWhiteSpacedTerms(string $searchString): array
    {
        return array(
            // Prepending white space characters
            'Prepending ordinary space'                => array(" $searchString"),
            'Prepending tab'                           => array("\t$searchString"),
            'Prepending new line'                      => array("\n$searchString"),
            'Prepending carriage return'               => array("\r$searchString"),
            'Prepending NUL-byte'                      => array("\0$searchString"),
            'Prepending vertical tab'                  => array("\x0B$searchString"),
            // Appending white space characters
            'Appending ordinary space'                 => array("$searchString "),
            'Appending tab'                            => array("$searchString\t"),
            'Appending new line'                       => array("$searchString\n"),
            'Appending carriage return'                => array("$searchString\r"),
            'Appending NUL-byte'                       => array("$searchString\0"),
            'Appending vertical tab'                   => array("$searchString\x0B"),
            // Prepending and appending white space characters
            'Prepending and appending ordinary space'  => array(" $searchString "),
            'Prepending and appending tab'             => array("\t$searchString\t"),
            'Prepending and appending new line'        => array("\n$searchString\n"),
            'Prepending and appending carriage return' => array("\r$searchString\r"),
            'Prepending and appending NUL-byte'        => array("\0$searchString\0"),
            'Prepending and appending vertical tab'    => array("\x0B$searchString\x0B")
        );
    }
}
