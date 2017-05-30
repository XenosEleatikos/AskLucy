<?php
namespace AskLucy\Test;

use AskLucy\Query;
use AskLucy\Clause;

/**
 * Unit tests for the query.
 *
 * @see Query
 */
class QueryTest extends ClauseTest
{
    /**
     * Tests adding an optional clause with shouldHave().
     *
     * @return void
     */
    public function testShouldHave(): void
    {
        $query = new Query;

        $clauseMock = $this->getClauseMock('a');

        $clauseMock->expects($this->once())
            ->method('optional')
            ->willReturn(null);

        $clauseMock->expects($this->never())
            ->method('required');

        $clauseMock->expects($this->never())
            ->method('prohibited');

        $query->shouldHave($clauseMock);

        $this->assertSame(
            'a',
            (string) $query,
            'Expected the query to contain the optional clause "a".'
        );
    }

    /**
     * Tests adding a required clause with mustHave().
     *
     * @return void
     */
    public function testMustHave(): void
    {
        $query = new Query;

        $clauseMock = $this->getClauseMock('+a');

        $clauseMock->expects($this->never())
            ->method('optional');

        $clauseMock->expects($this->once())
            ->method('required')
            ->willReturn(null);

        $clauseMock->expects($this->never())
            ->method('prohibited');

        $query->mustHave($clauseMock);

        $this->assertSame(
            '+a',
            (string) $query,
            'Expected the query to contain the required clause "+a".'
        );
    }

    /**
     * Tests adding a prohibited clause with mustNotHave().
     *
     * @return void
     */
    public function testMustNotHave(): void
    {
        $query = new Query;

        $clauseMock = $this->getClauseMock('-a');

        $clauseMock->expects($this->never())
            ->method('optional');

        $clauseMock->expects($this->never())
            ->method('required');

        $clauseMock->expects($this->once())
            ->method('prohibited')
            ->willReturn(null);

        $query->mustNotHave($clauseMock);

        $this->assertSame(
            '-a',
            (string) $query,
            'Expected the query to contain the required clause "-a".'
        );
    }

    /**
     * Tests, if a test query contains brackets or not.
     *
     * @param Query  $query            A query
     * @param bool $shouldHaveBrackets True, if the query is expected to contain brackets, otherwise false
     *
     * @dataProvider dataProviderTest__toStringRendersBrackets
     */
    public function test__toStringRendersBrackets(Query $query, bool $shouldHaveBrackets): void
    {
        $this->assertSame(
            $shouldHaveBrackets,
            (bool) preg_match('/\(.+\)/', (string) $query)
        );
    }

    /**
     * Provides test data for test__toStringRendersBrackets.
     *
     * @return array
     */
    public function dataProviderTest__toStringRendersBrackets(): array
    {
        $query1 = new Query('field');
        $query1->required();
        $query1->add($this->getClauseMock('a'));
        $query1->add($this->getClauseMock('b'));

        $query2 = new Query('field');
        $query2->required();
        $query2->add($this->getClauseMock('a'));

        $query3 = new Query;
        $query3->required();
        $query3->add($this->getClauseMock('a'));
        $query3->add($this->getClauseMock('b'));

        $query4 = new Query;
        $query4->required();
        $query4->add($this->getClauseMock('a'));

        $query5 = new Query('field');
        $query5->prohibited();
        $query5->add($this->getClauseMock('a'));
        $query5->add($this->getClauseMock('b'));

        $query6 = new Query('field');
        $query6->prohibited();
        $query6->add($this->getClauseMock('a'));

        $query7 = new Query;
        $query7->prohibited();
        $query7->add($this->getClauseMock('a'));
        $query7->add($this->getClauseMock('b'));

        $query8 = new Query;
        $query8->prohibited();
        $query8->add($this->getClauseMock('a'));

        $query9 = new Query('field');
        $query9->add($this->getClauseMock('a'));
        $query9->add($this->getClauseMock('b'));

        $query10 = new Query('field');
        $query10->add($this->getClauseMock('a'));

        $query11 = new Query;
        $query11->add($this->getClauseMock('a'));
        $query11->add($this->getClauseMock('b'));

        $query12 = new Query;
        $query12->add($this->getClauseMock('a'));

        return [
            'Query with "required" operator, non-default field and more than one clause'   => [$query1, true],
            'Query with "required" operator, non-default field and a single clause'        => [$query2, false],
            'Query with "required" operator, default field and more than one clauses'      => [$query3, false],
            'Query with "required" operator, default field and a single clause'            => [$query4, false],
            'Query with "prohibited" operator, non-default field and more than one clause' => [$query5, true],
            'Query with "prohibited" operator, non-default field and a single clause'      => [$query6, false],
            'Query with "prohibited" operator, default field and more than one clauses'    => [$query7, false],
            'Query with "prohibited" operator, default field and a single clause'          => [$query8, false],
            'Query with default operator, non-default field and more than one clause'      => [$query9, false],
            'Query with default operator, non-default field and a single clause'           => [$query10, false],
            'Query with default operator, default field and more than one clauses'         => [$query11, false],
            'Query with default operator, default field and a single clause'               => [$query12, false],
        ];
    }

    /**
     * Tests, if __toString() renders a list of clauses using spaces as separators.
     *
     * @return void
     */
    public function test__toStringRendersClausesWithSeparator():void
    {
        $query = new Query;
        $query->add($this->getClauseMock('a'));
        $query->add($this->getClauseMock('b'));
        $query->add($this->getClauseMock('c'));

        $this->assertInternalType(
            'int',
            strpos($query, 'a b c'),
            'Expected the query to contain a space separated list of clauses.'
        );
    }

    /**
     * Returns a mock object for a clause.
     *
     * @param string $query A query returned by __toString()
     *
     * @return \PHPUnit_Framework_MockObject_MockObject|Clause
     */
    private function getClauseMock(string $query): \PHPUnit_Framework_MockObject_MockObject
    {
        $clauseMock = $this->getMockBuilder('AskLucy\Clause')
            ->getMockForAbstractClass();

        $clauseMock->expects($this->any())
            ->method('__toString')
            ->willReturn($query);

        return $clauseMock;
    }

    /**
     * Returns a query for testing.
     *
     * @param null|string $constructorArgumentField The constructor argument for the field
     *
     * @return Clause|Query
     */
    protected function getTestClause(?string $constructorArgumentField = null): Clause
    {
        $query = (null === $constructorArgumentField)
            ? new Query
            : new Query($constructorArgumentField);

        $query->add($this->getClauseMock('a'));

        return $query;
    }
}
