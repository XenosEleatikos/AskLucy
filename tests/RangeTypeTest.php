<?php
namespace LuceneQuery\Test;

use LuceneQuery\RangeType;
use PHPUnit\Framework\TestCase;

/**
 * Unit tests for the range type.
 *
 * @see RangeType
 */
class RangeTypeTest extends TestCase
{
    /**
     * Tests, if __construct() throws an exception for an invalid range type code given.
     *
     * @expectedException        \Exception
     * @expectedExceptionMessage Invalid range type "invalid"!
     *
     * @return void
     */
    public function test__constructThrowsException(): void
    {
        new RangeType('invalid');
    }
}
