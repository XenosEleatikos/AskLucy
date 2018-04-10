<?php
namespace AskLucy\Test\Expression\Clause;

use AskLucy\Expression\Clause\Clause;
use AskLucy\Expression\Clause\Term;
use AskLucy\Lucene;

/**
 * Unit tests for the term.
 *
 * @see Term
 */
class TermTest extends ClauseTest
{
    /**
     * Tests, if __construct() throws an exception for a given phrase containing spaces.
     *
     * @expectedException \Exception
     *
     * @return void
     */
    public function test__constructThrowsExceptionForGivenPhrase():void
    {
        Lucene::term('a search phrase');
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
        $term = Lucene::term($searchString);

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
     * @param string $searchString The term to be fuzzified
     * @param int    $distance     The distance parameter given to fuzzify()
     * @param string $expecteTerm  The expected term
     *
     * @dataProvider dataProviderTestFuzzify
     *
     * @return void
     */
    public function testFuzzify(string $searchString, int $distance, string $expecteTerm): void
    {
        $term   = Lucene::term($searchString);
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
            . '", if Damerau-Levenshtein distance '
            . $distance
            . ' given as parameter.'
        );
    }

    /**
     * Data provider for testFuzzify().
     *
     * @return array
     */
    public function dataProviderTestFuzzify(): array
    {
        return [
            'Single word term with distance 0' => ['term', 0, 'term'],
            'Single word term with distance 1' => ['term', 1, 'term~1'],
            'Single word term with distance 2' => ['term', 2, 'term~']
        ];
    }

    /**
     * Tests, if fuzzify() overwrites a priviously set Damerau-Levenshtein distance. Also tests, that in such a case
     * the term itself is returned for a fluent interface.
     *
     * @param string $searchString          The term to be fuzzified
     * @param int    $priviouslySetDistance The priviously set Damerau-Levenshtein distance
     * @param int    $distance              The distance parameter given to fuzzify()
     * @param string $expecteTerm           The expected term
     *
     * @dataProvider dataProvidertTestFuzzifyOverwritesPriviouslySetFuzziness
     *
     * @return void
     */
    public function testFuzzifyOverwritesPriviouslySetFuzziness(
        string $searchString,
        int $priviouslySetDistance,
        int $distance,
        string $expecteTerm
    ): void {
        $term = Lucene::term($searchString);
        $term->fuzzify($priviouslySetDistance);
        $result = $term->fuzzify($distance);

        $this->assertSame(
            $term,
            $result,
            'Expected fuzzify() to return the term itself for a fluent interface.'
        );

        $this->assertSame(
            $expecteTerm,
            (string) $term,
            'Expected fuzzify() to overwrite a priviously set Damerau-Levenshtein distance.'
        );
    }

    /**
     * Data provider for testFuzzifyOverwritesPriviouslySetFuzziness().
     *
     * @return array
     */
    public function dataProvidertTestFuzzifyOverwritesPriviouslySetFuzziness(): array
    {
        return [
            'Single word term with distance 0' => ['term', 1, 0, 'term'],
            'Single word term with distance 1' => ['term', 2, 1, 'term~1'],
            'Single word term with distance 2' => ['term', 1, 2, 'term~']
        ];
    }

    /**
     * Tests, if fuzzify() sets the default fuzziness, if no parameter is given. Also tests, if in that case the term
     * itself is returned for a fluent interface.
     *
     * @return void
     */
    public function testFuzzifyWithDefaultParameter()
    {
        $term = Lucene::term('term');
        $result = $term->fuzzify();

        $this->assertSame(
            $term,
            $result,
            'Expected fuzzify() to return the term itself for a fluent interface.'
        );

        $this->assertSame(
            'term~',
            (string) $term,
            'Expected fuzzy term to be "term~", if no Damerau-Levenshtein distance given as parameter.'
        );
    }

    /**
     * Tests, if the fuzziness is ignored, if a negative (and therefore invalid) Damerau-Levenshtein distance was given
     * to fuzzify(). Also tests, if in that case the term itself is returned for a fluent interface.
     *
     * @return void
     */
    public function testFuzzifySetsDistance0ForNegativeValueGiven(): void
    {
        $term = Lucene::term('term');
        $result = $term->fuzzify(-1);

        $this->assertSame(
            $term,
            $result,
            'Expected fuzzify() to return the term itself for a fluent interface.'
        );

        $this->assertSame(
            'term',
            (string) $term,
            'Expected the fuzziness to be ignored, if a negative (and therefore invalid) Damerau-Levenshtein distance was given to fuzzify().'
        );
    }

    /**
     * Tests, if the Damerau-Levenshtein distance 2 is set, if a too big value was given to fuzzify(). Also tests, if in
     * that case the term itself is returned for a fluent interface.
     *
     * @return void
     */
    public function testFuzzifySetsDistance2ForTooBigValueGiven(): void
    {
        $term = Lucene::term('term');
        $result = $term->fuzzify(3);

        $this->assertSame(
            $term,
            $result,
            'Expected fuzzify() to return the term itself for a fluent interface.'
        );

        $this->assertSame(
            'term~',
            (string) $term,
            'Expected the fuzziness to be 2, if a too big Damerau-Levenshtein distance (< 2) was given to fuzzify().'
        );
    }

    /**
     * Tests, if fuzzify0() sets the Damerau-Levenshtein distance 0 and returns the term itself for a fluent interface.
     *
     * @return void
     */
    public function testFuzzify0(): void
    {
        $term = Lucene::term('term');
        $result = $term->fuzzify0();

        $this->assertSame(
            $term,
            $result,
            'Expected fuzzify0() to return the term itself for a fluent interface.'
        );

        $this->assertSame(
            'term',
            (string) $term,
            'Expected fuzzify0() to set the Damerau-Levenshtein distance 0, and therefore the term to be "term".'
        );
    }

    /**
     * Tests, if fuzzify0() overwrites a priviously set Damerau-Levenshtein distance.
     *
     * @return void
     */
    public function testFuzzify0OverwritesPriviouslySetFuzziness(): void
    {
        $term = Lucene::term('term');
        $term->fuzzify1();
        $term->fuzzify0();
        $this->assertSame(
            'term',
            (string) $term,
            'Expected fuzzify0() overwrites a priviously set Damerau-Levenshtein distance.'
        );
    }

    /**
     * Tests, if fuzzify1() sets the Damerau-Levenshtein distance 1 and returns the term itself for a fluent interface.
     *
     * @return void
     */
    public function testFuzzify1(): void
    {
        $term = Lucene::term('term');
        $result = $term->fuzzify1();

        $this->assertSame(
            $term,
            $result,
            'Expected fuzzify1() to return the term itself for a fluent interface.'
        );

        $this->assertSame(
            'term~1',
            (string) $term,
            'Expected fuzzify1() to set the Damerau-Levenshtein distance 1, and therefore the term to be "term~1".'
        );
    }

    /**
     * Tests, if fuzzify1() overwrites a priviously set Damerau-Levenshtein distance.
     *
     * @return void
     */
    public function testFuzzify1OverwritesPriviouslySetFuzziness(): void
    {
        $term = Lucene::term('term');
        $term->fuzzify2();
        $term->fuzzify1();
        $this->assertSame(
            'term~1',
            (string) $term,
            'Expected fuzzify1() overwrites a priviously set Damerau-Levenshtein distance.'
        );
    }

    /**
     * Tests, if fuzzify2() sets the Damerau-Levenshtein distance 2 and returns the term itself for a fluent interface.
     *
     * @return void
     */
    public function testFuzzify2(): void
    {
        $term = Lucene::term('term');
        $result = $term->fuzzify2();

        $this->assertSame(
            $term,
            $result,
            'Expected fuzzify2() to return the term itself for a fluent interface.'
        );

        $this->assertSame(
            'term~',
            (string) $term,
            'Expected fuzzify2() to set the Damerau-Levenshtein distance 2, and therefore the term to be "term~".'
        );
    }

    /**
     * Tests, if fuzzify2() overwrites a priviously set Damerau-Levenshtein distance.
     *
     * @return void
     */
    public function testFuzzify2OverwritesPriviouslySetFuzziness(): void
    {
        $term = Lucene::term('term');
        $term->fuzzify1();
        $term->fuzzify2();
        $this->assertSame(
            'term~',
            (string) $term,
            'Expected fuzzify2() overwrites a priviously set Damerau-Levenshtein distance.'
        );
    }

    /**
     * Tests, if Term::__toString() returns the term given as constructor argument.
     *
     * @return void
     */
    public function test__toString(): void
    {
        $term = Lucene::term('term');

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
        $term = Lucene::term('');

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
     * Returns a term for testing.
     *
     * @param null|string $constructorArgumentField The constructor argument for the field
     *
     * @return Clause|Term
     */
    protected function getTestClause(?string $constructorArgumentField = null): Clause
    {
        $query = is_null($constructorArgumentField)
            ? Lucene::term('a')
            : Lucene::term('a', $constructorArgumentField);

        return $query;
    }
}
