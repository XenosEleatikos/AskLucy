<?php
namespace LuceneQuery\Test;

use LuceneQuery\Fuzziness;
use PHPUnit\Framework\TestCase;

/**
 * Unit tests for the fuzziness.
 *
 * @see Fuzziness
 */
class FuzzinessTest extends TestCase
{
    /**
     * Tests, if __construct() throws an exception, if the given Damerau-Levenshtein Distance is out of range.
     *
     * @param int $distance The Damerau-Levenshtein Distance as parameter for fuzzify
     *
     * @dataProvider dataProviderTestFuzzifyThrowsExceptionForInvalidDistance
     *
     * @expectedException \Exception
     *
     * @return void
     */
    public function test__constructThrowsException(int $distance): void
    {
        new Fuzziness($distance);
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
}
