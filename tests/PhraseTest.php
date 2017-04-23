<?php
namespace LuceneQuery\Test;

use LuceneQuery\Field;
use LuceneQuery\Phrase;
use PHPUnit\Framework\TestCase;

/**
 * Unit tests for the phrase.
 *
 * @see Phrase
 */
class PhraseTest extends TestCase
{
    /**
     * Tests, if phrases get quoted.
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
     * Tests, if setField() specifies a field for a phrase, and if the fielded phrase is rendered correctly by
     * __toString(). Also tests, if setField() returns the phrase itself for a fluent interface.
     *
     * @return void
     */
    public function testSetField(): void
    {
        $phrase = new Phrase('a search phrase');
        $result = $phrase->setField(new Field('field'));

        $this->assertSame(
            $phrase,
            $result,
            'Expected setField() to return the phrase itself for a fluent interface.'
        );
        $this->assertSame(
            'field:"a search phrase"',
            (string) $phrase
        );
    }

    /**
     * Data provider for testFuzzifyTerm().
     *
     * @return array
     */
    public function dataProviderTestFuzzifyTerm(): array
    {
        return [
            'Single word term with distance 0' => ['term', 0, 'term'],
            'Single word term with distance 1' => ['term', 1, 'term~1'],
            'Single word term with distance 2' => ['term', 2, 'term~'],
            'Phrase with distance 0'           => ['a search string', 0, '"a search string"'],
            'Phrase with distance 1'           => ['a search string', 1, '"a~1 search~1 string~1"'],
            'Phrase with distance 2'           => ['a search string', 2, '"a~ search~ string~"'],
        ];
    }
}
