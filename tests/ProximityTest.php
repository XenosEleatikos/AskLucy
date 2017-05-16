<?php
namespace LuceneQuery\Test;

use LuceneQuery\Proximity;
use PHPUnit\Framework\TestCase;

/**
 * Unit tests for the primitive query.
 *
 * @see Proximity
 */
class ProximityTest extends TestCase
{
    /**
     * Tests, if __construct() throws an exception for a negative number given.
     *
     * @expectedException        \Exception
     * @expectedExceptionMessage The given term distance must be positive!
     *
     * @return void
     */
    public function test__constructThrowsException(): void
    {
        new Proximity(-1);
    }
}
