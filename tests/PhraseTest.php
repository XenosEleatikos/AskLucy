<?php
namespace LuceneQuery\Test;

use LuceneQuery\Clause;
use LuceneQuery\Phrase;
use LuceneQuery\Query;
use LuceneQuery\Test\Property\FieldTraitTest;
use LuceneQuery\Test\Property\OperatorTraitTest;
use PHPUnit\Framework\TestCase;

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
        $phrase = new Phrase('a search phrase');

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
        $phrase = new Phrase('a search phrase');
        $phrase->setProximity(1);

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
        $phrase = new Phrase('a search phrase');

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
        $phrase = new Phrase('a search phrase');
        $phrase->setProximity(0);

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
        $phrase = new Phrase('a search phrase');
        $phrase->setProximity();

        $this->assertRegExp(
            '/^[^~]+$/',
            (string) $phrase,
            'Expected that __toString() doesn\'t render the proximity operator "~", when setProximity was called without parameter.'
        );
    }

    /**
     * Tests, if setProximity() overwrites the proximity set before and __toString() renders only the last set proximity.
     *
     * @return void
     */
    public function testSetProximityOverwritesProximitySetBeforeAnd__toStringRendersLastSetProximityOnly(): void
    {
        $phrase = new Phrase('a search phrase');
        $phrase->setProximity(1);
        $phrase->setProximity(2);

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
        $query = (null === $constructorArgumentField)
            ? new Phrase('a b')
            : new Phrase('a b', $constructorArgumentField);

        return $query;
    }
}
