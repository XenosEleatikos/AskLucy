<?php
namespace AskLucy\Test\Expression\Clause;

use AskLucy\Expression\Clause\Query;
use AskLucy\Expression\Clause\Clause;
use AskLucy\Lucene;
use PHPUnit\Framework\MockObject\MockObject;

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
        $query = Lucene::query();

        $clauseMock = $this->getClauseMock('a');

        $clauseMock->expects($this->once())
            ->method('optional');

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
        $query = Lucene::query();

        $clauseMock = $this->getClauseMock('+a');

        $clauseMock->expects($this->never())
            ->method('optional');

        $clauseMock->expects($this->once())
            ->method('required');

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
        $query = Lucene::query();

        $clauseMock = $this->getClauseMock('-a');

        $clauseMock->expects($this->never())
            ->method('optional');

        $clauseMock->expects($this->never())
            ->method('required');

        $clauseMock->expects($this->once())
            ->method('prohibited');

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
        $query1 = Lucene::query('field')
            ->required()
            ->add($this->getClauseMock('a'))
            ->add($this->getClauseMock('b'));

        $query2 = Lucene::query('field')
            ->required()
            ->add($this->getClauseMock('a'));

        $query3 = Lucene::query()
            ->required()
            ->add($this->getClauseMock('a'))
            ->add($this->getClauseMock('b'));

        $query4 = Lucene::query()
            ->required()
            ->add($this->getClauseMock('a'));

        $query5 = Lucene::query('field')
            ->prohibited()
            ->add($this->getClauseMock('a'))
            ->add($this->getClauseMock('b'));

        $query6 = Lucene::query('field')
            ->prohibited()
            ->add($this->getClauseMock('a'));

        $query7 = Lucene::query()
            ->prohibited()
            ->add($this->getClauseMock('a'))
            ->add($this->getClauseMock('b'));

        $query8 = Lucene::query()
            ->prohibited()
            ->add($this->getClauseMock('a'));

        $query9 = Lucene::query('field')
            ->add($this->getClauseMock('a'))
            ->add($this->getClauseMock('b'));

        $query10 = Lucene::query('field')
            ->add($this->getClauseMock('a'));

        $query11 = Lucene::query()
            ->add($this->getClauseMock('a'))
            ->add($this->getClauseMock('b'));

        $query12 = Lucene::query()
            ->add($this->getClauseMock('a'));

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
        $query = Lucene::query()
            ->add($this->getClauseMock('a'))
            ->add($this->getClauseMock('b'))
            ->add($this->getClauseMock('c'));

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
     * @return MockObject|Clause
     */
    private function getClauseMock(string $query): MockObject
    {
        $clauseMock = $this->getMockBuilder(Clause::class)
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
        $query = is_null($constructorArgumentField)
            ? Lucene::query()
            : Lucene::query($constructorArgumentField);

        $query->add($this->getClauseMock('a'));

        return $query;
    }
}
