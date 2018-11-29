<?php
namespace AskLucy\Test\Expression\Clause;

use AskLucy\Expression\Clause\Clause;
use AskLucy\Expression\Clause\Phrase;
use AskLucy\Lucene;

/**
 * Unit tests for the phrase.
 *
 * @see Phrase
 */
class PhraseTest extends ClauseTest
{
    /**
     * Tests, if phrases getting quoted.
     *
     * @return void
     */
    public function test__toStringRendersPhrases(): void
    {
        $phrase = Lucene::phrase('');

        $this->assertSame(
            '',
            (string) $phrase,
            'Expected an empty string.'
        );
    }

    /**
     * Tests, if __toString() renders an empty phrase.
     *
     * @return void
     */
    public function test__toStringRendersEmptyPhrase(): void
    {
        $phrase = Lucene::phrase('a search phrase');

        $this->assertRegExp(
            '("a search phrase")',
            (string) $phrase,
            'Expected phrases to get quoted.'
        );
    }

    /**
     * Tests, if setProximity() sets and __toString() renders the proximity correctly.
     *
     * @return void
     */
    public function test__toStringRendersProximity(): void
    {
        $phrase = Lucene::phrase('a search phrase')
            ->setProximity(1);

        $this->assertRegExp(
            '/(a search phrase).?(~1)/',
            (string) $phrase,
            'Expected that __toString() appends "~1" to the phrase, because setProximity() was called with parameter 1.'
        );
    }

    /**
     * Tests, if __toString() doesn't render the proximity operator "~", when setProximity() wasn't called.
     *
     * @return void
     */
    public function test__toStringDoesNotRenderProximityOperatorIfSetProximityWasNotCalled(): void
    {
        $phrase = Lucene::phrase('a search phrase');

        $this->assertRegExp(
            '/^[^~]+$/',
            (string) $phrase,
            'Expected that __toString() doesn\'t render the proximity operator "~", when setProximity wasn\'t called.'
        );
    }

    /**
     * Tests, if __toString() doesn't render the proximity operator "~", when setProximity() was called with parameter 0.
     *
     * @return void
     */
    public function test__toStringDoesNotRenderProximityOperatorIfSetProximityWasCalledWith0(): void
    {
        $phrase = Lucene::phrase('a search phrase')
            ->setProximity(0);

        $this->assertRegExp(
            '/^[^~]+$/',
            (string) $phrase,
            'Expected that __toString() doesn\'t render the proximity operator "~", when setProximity was called with parameter 0.'
        );
    }

    /**
     * Tests, if __toString() doesn't render the proximity operator "~", when setProximity() was called without parameter.
     *
     * @return void
     */
    public function test__toStringDoesNotRenderProximityOperatorIfSetProximityWasCalledWithoutParameter(): void
    {
        $phrase = Lucene::phrase('a search phrase')
            ->setProximity();

        $this->assertRegExp(
            '/^[^~]+$/',
            (string) $phrase,
            'Expected that __toString() doesn\'t render the proximity operator "~", when setProximity was called without parameter.'
        );
    }

    /**
     * Tests, if __toString() doesn't render the proximity operator "~" for a single term phrase.
     *
     * @return void
     */
    public function test__toStringDoesNotRenderProximityOperatorForSingleTermPhrase(): void
    {
        $phrase = Lucene::phrase('lucene')
            ->setProximity(1);

        $this->assertRegExp(
            '/^[^~]+$/',
            (string) $phrase,
            'Expected that __toString() doesn\'t render the proximity operator "~" for a single word term.'
        );
    }

    /**
     * Tests, if setProximity() overwrites the proximity set before and __toString() renders only the last set proximity.
     *
     * @return void
     */
    public function testSetProximityOverwritesProximitySetBeforeAnd__toStringRendersLastSetProximityOnly(): void
    {
        $phrase = Lucene::phrase('a search phrase')
            ->setProximity(1)
            ->setProximity(2);

        $this->assertRegExp(
            '/(~2)/',
            (string) $phrase,
            'Expected that the __toString() renders the last set proximity, "~2".'
        );

        $this->assertRegExp(
            '/^[^1]+$/',
            (string) $phrase,
            'Expected that the proximity 1 was overwritten by 2 and that __toString() only renders the last set proximity, "~2".'
        );
    }

    /**
     * Returns a phrase for testing.
     *
     * @param null|string $constructorArgumentField The constructor argument for the field
     *
     * @return Clause|Phrase
     */
    protected function getTestClause(?string $constructorArgumentField = null): Clause
    {
        $query = is_null($constructorArgumentField)
            ? Lucene::phrase('a b')
            : Lucene::phrase('a b', $constructorArgumentField);

        return $query;
    }
}
