<?php
namespace LuceneQuery\Test;

use LuceneQuery\Phrase;
use LuceneQuery\Test\Property\FieldTraitTest;
use LuceneQuery\Test\Property\OperatorTraitTest;
use PHPUnit\Framework\TestCase;

/**
 * Unit tests for the phrase.
 *
 * @see Phrase
 */
class PhraseTest extends TestCase
{
    use FieldTraitTest;
    use OperatorTraitTest;

    /**
     * Tests, if phrases getting quoted.
     *
     * @return void
     */
    public function test__toString(): void
    {
        $phrase = new Phrase('a search phrase');

        $this->assertSame(
            '"a search phrase"',
            (string) $phrase,
            'Expected phrases to get quoted.'
        );
    }

    /**
     * Returns a query for testing.
     *
     * @param null|string $constructorArgumentField The constructor argument for the field
     *
     * @return Phrase
     */
    protected function getTestClause(?string $constructorArgumentField = null): Phrase
    {
        $query = (null === $constructorArgumentField)
            ? new Phrase('a b')
            : new Phrase('a b', $constructorArgumentField);

        return $query;
    }
}
