<?php
namespace LuceneQuery\Test;

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
}
