<?php
namespace AskLucy\Test\Expression\Clause;

use AskLucy\Expression\Clause\Clause;
use AskLucy\Expression\Clause\Phrase;
use AskLucy\Expression\Clause\Range;
use AskLucy\Lucene;

/**
 * Unit tests for the phrase.
 *
 * @see Phrase
 */
class RangeTest extends ClauseTest
{
    /**
     * Tests, if the constructor sets the lower and the upper bound, if the range type is inclusive by default, and if
     * __toString() renders the range correctly for this minimum set of input.
     *
     * @return void
     */
    public function test__constructSetsRage(): void
    {
        $range = $this->getTestClause();

        $this->assertSame(
            '[a TO b]',
            (string) $range,
            'Expected range to be rendered as "[a TO b]", if "a" and "b" where given as constructor arguments for the lower and the upper bound.'
        );
    }

    /**
     * Tests, if the constructor sets the lower and the upper bound, if the range type is inclusive by default, and if
     * __toString() renders the range correctly for this minimum set of input.
     *
     * @return void
     */
    public function test__constructSetsRageWithField(): void
    {
        $range = $this->getTestClause('name');

        $this->assertSame(
            'name:',
            substr($range, 0, 5),
            'Expected that "name:" is prepended to the range, if the field "name" was given as constructor argument.'
        );
    }

    /**
     * Tests, if __toString() renders the range correctly with curly brackets, if exclusive() was called.
     *
     * @return void
     */
    public function testExclusive(): void
    {
        $range = $this->getTestClause();
        $range->exclusive();

        $this->assertSame(
            '{a TO b}',
            (string) $range,
            'Expected exclusive range to be rendered as "{a TO b}".'
        );
    }

    /**
     * Tests, if exclusive() returns the range itself for a fluent interface.
     *
     * @return void
     */
    public function testExclusiveReturnsSelf(): void
    {
        $range = $this->getTestClause();

        $this->assertSame(
            $range,
            $range->exclusive(),
            'Expected that exclusive() returns the range itself for a fluent interface.'
        );
    }

    /**
     * Tests, if exclusive() overwrites inclusive(), and if the exclusive range is rendered with curly brackets.
     *
     * @return void
     */
    public function testExclusiveOverwritesInclusive(): void
    {
        $range = $this->getTestClause();
        $range->inclusive();
        $range->exclusive();

        $this->assertRegExp(
            '/{*+}/',
            (string) $range,
            'Expected that exclusive() overwrites inclusive().'
        );
    }

    /**
     * Tests, if __toString() renders the range correctly with square brackets, if inclusive() was called.
     *
     * @return void
     */
    public function testInclusive(): void
    {
        $range = $this->getTestClause();
        $range->inclusive();

        $this->assertSame(
            '[a TO b]',
            (string) $range,
            'Expected exclusive range to be rendered as "[a TO b]".'
        );
    }

    /**
     * Tests, if exclusive() returns the range itself for a fluent interface.
     *
     * @return void
     */
    public function testInclusiveReturnsSelf(): void
    {
        $range = $this->getTestClause();

        $this->assertSame(
            $range,
            $range->inclusive(),
            'Expected that inclusive() returns the range itself for a fluent interface.'
        );
    }

    /**
     * Tests, if inclusive() overwrites exclusive(), and if the inclusive range is rendered with square brackets.
     *
     * @return void
     */
    public function testInclusiveOverwritesExclusive(): void
    {
        $range = $this->getTestClause();
        $range->exclusive();
        $range->inclusive();

        $this->assertRegExp(
            '/\[*+\]/',
            (string) $range,
            'Expected that inclusive() overwrites exclusive().'
        );
    }

    /**
     * Returns a range for testing.
     *
     * @param null|string $constructorArgumentField The constructor argument for the field
     *
     * @return Clause|Range
     */
    protected function getTestClause(?string $constructorArgumentField = null): Clause
    {
        return is_null($constructorArgumentField)
            ? Lucene::range('a', 'b')
            : Lucene::range('a', 'b', $constructorArgumentField);
    }
}
