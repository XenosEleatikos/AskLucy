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
     * Tests, if setField() specifies a field for a query and returns the query itself for a fluent interface.
     *
     * @return void
     */
    public function testSetField(): void
    {
        $query  = new Query($this->getClauseMock('term'));
        $result = $query->setField('field');

        $this->assertSame(
            $query,
            $result,
            'Expected setField() to return the query itself for a fluent interface.'
        );

        $this->assertSame(
            'field:(term)',
            (string) $query,
            'Expected fielded query to be "field:(term)".'
        );
    }

    /**
     * Tests, if addOptionalClause() adds a given query and returns the query itself for a fluent interface.
     *
     * @return void
     */
    public function testAddOptionalQuery(): void
    {
        $a = $this->getClauseMock('a');
        $b = $this->getClauseMock('b');

        $query = new Query($a);
        $result = $query->addOptionalClause($b);

        $this->assertSame(
            $query,
            $result,
            'Expected addOptionalClause() to return the query itself for a fluent interface.'
        );

        $this->assertSame(
            '(a b)',
            (string) $query,
            'Expected conjuncted query to be "(a b)"'
        );
    }

    /**
     * Tests, if addRequiredClause() adds a given query with plus (+) prefix and returns the conjuncted query itself
     * for a fluent interface.
     *
     * @return void
     */
    public function testAddRequiredQuery(): void
    {
        $a = $this->getClauseMock('a');
        $b = $this->getClauseMock('b');

        $query = new Query($a);
        $result = $query->addRequiredClause($b);

        $this->assertSame(
            $query,
            $result,
            'Expected addRequiredClause() to return the query itself for a fluent interface.'
        );

        $this->assertSame(
            '(a +b)',
            (string) $query,
            'Expected conjuncted query to be "(a +b)"'
        );
    }

    /**
     * Tests, if addProhibitedClause() adds a given query with minus (-) prefix and returns the conjuncted query itself
     * for a fluent interface.
     *
     * @return void
     */
    public function testAddProhibitedQuery(): void
    {
        $a = $this->getClauseMock('a');
        $b = $this->getClauseMock('b');

        $query = new Query($a);
        $result = $query->addProhibitedClause($b);

        $this->assertSame(
            $query,
            $result,
            'Expected addProhibitedClause() to return the query itself for a fluent interface.'
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
        $a = $this->getClauseMock('a');
        $b = $this->getClauseMock('b');

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
        $a = $this->getClauseMock('a');
        $b = $this->getClauseMock('b');

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
        $a = $this->getClauseMock('a');
        $b = $this->getClauseMock('b');

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
    private function getClauseMock(string $query): \PHPUnit_Framework_MockObject_MockObject
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
