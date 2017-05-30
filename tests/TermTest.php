<?php
namespace AskLucy\Test;

use AskLucy\Clause;
use AskLucy\Term;

/**
 * Unit tests for the term.
 *
 * @see Term
 */
class TermTests extends ClauseTest
{
    /**
     * Tests, if __construct() throws an expection for a given phrase containing spaces.
     *
     * @expectedException \Exception
     *
     * @return void
     */
    public function test__constructThrowsExceptionForGivenPhrase():void
    {
        new Term('a search phrase');
    }

    /**
     * Tests, if whitespace (or similar characters) are stripped from the beginning and end of the term.
     *
     * @dataProvider dataProviderTestTrimSearchString
     *
     * @return void
     */
    public function testTrimSearchString($searchString): void
    {
        $term = new Term($searchString);

        $this->assertSame(
            'term',
            (string) $term,
            'Expected leading and ending whitespace characters to be stripped from the term.'
        );
    }

    /**
     * Tests, if fuzzify() sets the fuzziness, and if the fuzzified term is rendered correctly by __toString(). Also
     * tests, if fuzzify() returns the term itself for a fluent interface.
     *
     * @param string $searchString   The term to be fuzzified
     * @param int    $distance       The distance parameter given to fuzzify()
     * @param string $expecteTerm    The expected term
     *
     * @dataProvider dataProviderTestFuzzifyTerm
     *
     * @return void
     */
    public function testFuzzify(string $searchString, int $distance, string $expecteTerm): void
    {
        $term   = new Term($searchString);
        $result = $term->fuzzify($distance);

        $this->assertSame(
            $term,
            $result,
            'Expected fuzzify() to return the term itself for a fluent interface.'
        );

        $this->assertSame(
            $expecteTerm,
            (string) $term,
            'Expected term to be "'
            . $expecteTerm
            . '", if Damerau-Levenshtein Distance '
            . $distance
            . ' given as parameter.'
        );
    }

    /**
     * Tests, if fuzzify() sets the default fuzziness, if no parameter is given. Also tests, if in that case the term
     * itself is returned for a fluent interface.
     *
     * @return void
     */
    public function testFuzzifyWithDefaultParameter()
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
     * Tests, if fuzzify() throws an expection for an invalid Damerau-Levenshtein Distance given.
     *
     * @param int $distance The Damerau-Levenshtein Distance as parameter for fuzzify
     *
     * @dataProvider dataProviderTestFuzzifyThrowsExceptionForInvalidDistance
     *
     * @expectedException \Exception
     *
     * @return void
     */
    public function testFuzzifyThrowsExceptionForInvalidDistance(int $distance): void
    {
        $term = new Term('term');
        $term->fuzzify($distance);
    }

    /**
     * Data provider for testFuzzifyThrowsExceptionForInvalidDistance().
     *
     * @return array
     */
    public function dataProviderTestFuzzifyThrowsExceptionForInvalidDistance(): array
    {
        return [
            'Negative distance' => [-1],
            'To big distance'   => [3],
        ];
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
     * Data provider for testTrimSearchString().
     *
     * @return array
     */
    public function dataProviderTestTrimSearchString(): array
    {
        return [
            // Prepending white space characters
            'Prepending ordinary space'                => array(" term"),
            'Prepending tab'                           => array("\tterm"),
            'Prepending new line'                      => array("\nterm"),
            'Prepending carriage return'               => array("\rterm"),
            'Prepending NUL-byte'                      => array("\0term"),
            'Prepending vertical tab'                  => array("\x0Bterm"),
            // Appending white space characters
            'Appending ordinary space'                 => array("term "),
            'Appending tab'                            => array("term\t"),
            'Appending new line'                       => array("term\n"),
            'Appending carriage return'                => array("term\r"),
            'Appending NUL-byte'                       => array("term\0"),
            'Appending vertical tab'                   => array("term\x0B"),
            // Prepending and appending white space characters
            'Prepending and appending ordinary space'  => array(" term "),
            'Prepending and appending tab'             => array("\tterm\t"),
            'Prepending and appending new line'        => array("\nterm\n"),
            'Prepending and appending carriage return' => array("\rterm\r"),
            'Prepending and appending NUL-byte'        => array("\0term\0"),
            'Prepending and appending vertical tab'    => array("\x0Bterm\x0B")
        ];
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
            'Single word term with distance 2' => ['term', 2, 'term~']
        ];
    }

    /**
     * Returns a term for testing.
     *
     * @param null|string $constructorArgumentField The constructor argument for the field
     *
     * @return Clause|Term
     */
    protected function getTestClause(?string $constructorArgumentField = null): Clause
    {
        $query = (null === $constructorArgumentField)
            ? new Term('a')
            : new Term('a', $constructorArgumentField);

        return $query;
    }
}
