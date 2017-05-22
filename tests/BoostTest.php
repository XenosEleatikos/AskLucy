<?php
namespace LuceneQuery\Test;

use LuceneQuery\Boost;
use LuceneQuery\Fuzziness;
use PHPUnit\Framework\TestCase;

/**
 * Unit tests for the boost.
 *
 * @see Fuzziness
 */
class BoostTest extends TestCase
{
    /**
     * Tests, if __construct() throws an exception, if the boost value is out of range.
     *
     * @param float $bootValue The boost value as constructor argument
     *
     * @dataProvider dataProviderTest__constructThrowsExceptionForInvalidValue
     *
     * @expectedException \Exception
     *
     * @return void
     */
    public function test__constructThrowsException(float $bootValue): void
    {
        new Boost($bootValue);
    }

    /**
     * Data provider for test__constructThrowsException().
     *
     * @return array
     */
    public function dataProviderTest__constructThrowsExceptionForInvalidValue(): array
    {
        return [
            'Negative value' => [-1.0],
            'Zero value'     => [0.0],
        ];
    }
}
