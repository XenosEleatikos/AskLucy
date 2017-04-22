<?php
namespace LuceneQuery\Test;

use LuceneQuery\Query;
use LuceneQuery\Clause;
use PHPUnit\Framework\TestCase;

/**
 * Unit tests for the query.
 *
 * @see Query
 */
class QueryTest extends TestCase
{
    /**
     * Tests, if addFields() adds a field to a query.
     *
     * @return void
     */
    public function testAddField(): void
    {
        $query = new Query($this->getQueryMock('term'));
        $query->addField('field');

        $this->assertSame(
            'field:(term)',
            (string) $query,
            'Expected fielded query to be "field:(term)".'
        );
    }

    /**
     * Tests, if addOptionalQuery() adds a given query and returns the query itself for a fluent interface.
     *
     * @return void
     */
    public function testAddOptionalQuery(): void
    {
        $a = $this->getQueryMock('a');
        $b = $this->getQueryMock('b');

        $query = new Query($a);
        $result = $query->addOptionalQuery($b);

        $this->assertSame(
            $query,
            $result,
            'Expected addOptionalQuery() to return the query itself for a fluent interface.'
        );

        $this->assertSame(
            '(a b)',
            (string) $query,
            'Expected conjuncted query to be "(a b)"'
        );
    }

    /**
     * Tests, if addRequiredQuery() adds a given query with plus (+) prefix and returns the conjuncted query itself
     * for a fluent interface.
     *
     * @return void
     */
    public function testAddRequiredQuery(): void
    {
        $a = $this->getQueryMock('a');
        $b = $this->getQueryMock('b');

        $query = new Query($a);
        $result = $query->addRequiredQuery($b);

        $this->assertSame(
            $query,
            $result,
            'Expected addRequiredQuery() to return the query itself for a fluent interface.'
        );

        $this->assertSame(
            '(a +b)',
            (string) $query,
            'Expected conjuncted query to be "(a +b)"'
        );
    }

    /**
     * Tests, if addProhibitedQuery() adds a given query with minus (-) prefix and returns the conjuncted query itself
     * for a fluent interface.
     *
     * @return void
     */
    public function testAddProhibitedQuery(): void
    {
        $a = $this->getQueryMock('a');
        $b = $this->getQueryMock('b');

        $query = new Query($a);
        $result = $query->addProhibitedQuery($b);

        $this->assertSame(
            $query,
            $result,
            'Expected addProhibitedQuery() to return the query itself for a fluent interface.'
        );

        $this->assertSame(
            '(a -b)',
            (string) $query,
            'Expected conjuncted query to be "(a -b)"'
        );
    }

    /**
     * Tests, if _and() creates a well formed and-query and returns the query itself for a fluent interface.
     *
     * @return void
     */
    public function test_and(): void
    {
        $a = $this->getQueryMock('a');
        $b = $this->getQueryMock('b');

        $query = new Query($a);
        $result = $query->_and($b);

        $this->assertSame(
            $query,
            $result,
            'Expected _and() to return the query itself for a fluent interface.'
        );

        $this->assertSame(
            '(a AND b)',
            (string) $query,
            'Expected and-query to be "(a AND b)"'
        );
    }

    /**
     * Tests, if _or() creates a well formed or-query and returns the query itself for a fluent interface.
     *
     * @return void
     */
    public function test_or(): void
    {
        $a = $this->getQueryMock('a');
        $b = $this->getQueryMock('b');

        $query = new Query($a);
        $result = $query->_or($b);

        $this->assertSame(
            $query,
            $result,
            'Expected _and() to return the query itself for a fluent interface.'
        );

        $this->assertSame(
            '(a OR b)',
            (string) $query,
            'Expected or-query to be "(a OR b)"'
        );
    }

    /**
     * Tests, if _not() creates a well formed not-query and returns the query itself for a fluent interface.
     *
     * @return void
     */
    public function test_not(): void
    {
        $a = $this->getQueryMock('a');
        $b = $this->getQueryMock('b');

        $query = new Query($a);
        $result = $query->_not($b);

        $this->assertSame(
            $query,
            $result,
            'Expected _not() to return the query itself for a fluent interface.'
        );

        $this->assertSame(
            '(a NOT b)',
            (string) $query,
            'Expected or-query to be "(a NOT b)"'
        );
    }

    /**
     * Returns a mock object for the query interface.
     *
     * @param string $query A query returned by __toString()
     *
     * @return \PHPUnit_Framework_MockObject_MockObject|Clause
     */
    private function getQueryMock(string $query): \PHPUnit_Framework_MockObject_MockObject
    {
        $queryMock = $this->getMockBuilder('LuceneQuery\Clause')
            ->setMethods(['__toString'])
            ->getMockForAbstractClass();

        $queryMock->expects($this->once())
            ->method('__toString')
            ->willReturn($query);

        return $queryMock;
    }
}
