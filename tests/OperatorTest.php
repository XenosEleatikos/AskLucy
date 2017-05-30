<?php
namespace AskLucy\Test;

use AskLucy\Operator;
use PHPUnit\Framework\TestCase;

/**
 * Unit tests for the primitive query.
 *
 * @see Operator
 */
class OperatorTest extends TestCase
{
    /**
     * Tests, if __construct() throws an exception for an invalid operator symbol given.
     *
     * @expectedException        \Exception
     * @expectedExceptionMessage Invalid logical operator "X"!
     *
     * @return void
     */
    public function test__constructThrowsException(): void
    {
        new Operator('X');
    }
}
