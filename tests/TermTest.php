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
            'Asserted leading and ending whitespace characters to be stripped from the term.'
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
            'Asserted leading and ending whitespace characters to be stripped from the phrase.'
        );
    }

    /**
     * Tests, if addField() adds a field to a single term.
     *
     * @return void
     */
    public function testAddFieldToSingleTerm(): void
    {
        $term = new Term('term');
        $term->addField(new Field('field'));

        $this->assertSame(
            'field:term',
            (string) $term
        );
    }

    /**
     * Tests, if addField() adds a field to a phrase of several words.
     *
     * @return void
     */
    public function testAddFieldToPhrase(): void
    {
        $term = new Term('a search phrase');
        $term->addField(new Field('field'));

        $this->assertSame(
            'field:"a search phrase"',
            (string) $term
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
            'Asserted term to be "term".'
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
            'Asserted term to be an empty string.'
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
            'Asserted phrases containing several words being quoted.'
        );
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
