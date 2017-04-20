<?php
namespace LuceneQuery\Test;

use LuceneQuery\Field;
use LuceneQuery\Query;
use LuceneQuery\Term;
use PHPUnit\Framework\TestCase;

/**
 * Unit tests for the primitive query.
 *
 * @see Query
 */
class QueryTest extends TestCase
{
    /**
     * Tests, if a query with fielded term is constructed and converted to string correctly.
     *
     * @return void
     */
    public function test__toStringWithFieldedTerm(): void
    {
        $query = new Query(new Term('term'), new Field('field'));

        $this->assertSame(
            'field:term',
            (string) $query,
            'Asserted query with fielded term to be "field:term".'
        );
    }

    /**
     * Tests, if a query with unfielded term is constructed and converted to string correctly.
     *
     * @return void
     */
    public function test__toStringWithoutFieldGiven(): void
    {
        $query = new Query(new Term('term'));

        $this->assertSame(
            'term',
            (string) $query,
            'Asserted query with unfielded term to be "term".'
        );
    }

    /**
     * Tests, if a query with an empty field name is constructed and converted to string correctly.
     *
     * @return void
     */
    public function test__toStringWithEmptyFieldGiven(): void
    {
        $query = new Query(new Term('term'), new Field);

        $this->assertSame(
            'term',
            (string) $query,
            'Asserted query with empty field name to be "term".'
            );
    }
}
