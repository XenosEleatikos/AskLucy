<?php
namespace LuceneQuery\Test;

use LuceneQuery\CombinedQuery;
use LuceneQuery\QueryInterface;
use PHPUnit\Framework\TestCase;

/**
 * Unit tests for the primitive query.
 *
 * @see CombinedQuery
 */
class CombinedQueryTest extends TestCase
{
    /**
     * Tests, if _and() creates a well form and-query.
     *
     * @return void
     */
    public function test_and(): void
    {
        $a = $this->getQueryMock('a');
        $b = $this->getQueryMock('b');

        $combinedQuery = new CombinedQuery($a);
        $combinedQuery->_and($b);

        $this->assertSame(
            '(a AND b)',
            (string) $combinedQuery,
            'Asserted and-query to be "(a AND b)"'
        );
    }

    /**
     * Tests, if _or() creates a well form or-query.
     *
     * @return void
     */
    public function test_or(): void
    {
        $a = $this->getQueryMock('a');
        $b = $this->getQueryMock('b');

        $combinedQuery = new CombinedQuery($a);
        $combinedQuery->_or($b);

        $this->assertSame(
            '(a OR b)',
            (string) $combinedQuery,
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
