<?php
namespace LuceneQuery\Test;

use LuceneQuery\Query;
use LuceneQuery\QueryInterface;
use PHPUnit\Framework\TestCase;

/**
 * Unit tests for the query.
 *
 * @see Query
 */
class QueryTest extends TestCase
{
    /**
     * Tests, if _and() creates a well formed and-query.
     *
     * @return void
     */
    public function test_and(): void
    {
        $a = $this->getQueryMock('a');
        $b = $this->getQueryMock('b');

        $query = new Query($a);
        $query->_and($b);

        $this->assertSame(
            '(a AND b)',
            (string) $query,
            'Asserted and-query to be "(a AND b)"'
        );
    }

    /**
     * Tests, if _or() creates a well formed or-query.
     *
     * @return void
     */
    public function test_or(): void
    {
        $a = $this->getQueryMock('a');
        $b = $this->getQueryMock('b');

        $query = new Query($a);
        $query->_or($b);

        $this->assertSame(
            '(a OR b)',
            (string) $query,
            'Asserted or-query to be "(a OR b)"'
        );
    }

    /**
     * Returns a mock object for the query interface.
     *
     * @param string $query A query returned by __toString()
     *
     * @return \PHPUnit_Framework_MockObject_MockObject|QueryInterface
     */
    private function getQueryMock(string $query): \PHPUnit_Framework_MockObject_MockObject
    {
        $queryMock = $this->getMockBuilder('LuceneQuery\QueryInterface')
            ->setMethods(['__toString'])
            ->getMockForAbstractClass();

        $queryMock->expects($this->once())
            ->method('__toString')
            ->willReturn($query);

        return $queryMock;
    }
}
