<?php

declare(strict_types=1);

namespace AskLucy\Test\Expression\Clause;

use AskLucy\Exception\InvalidArgumentException;
use AskLucy\Expression\Clause\Clause;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

/**
 * Tests the behavior all clauses have in common: field, operator and boost.
 *
 * Every test compares the complete rendered clause, so that the position of each part is verified as well.
 */
abstract class ClauseTestCase extends TestCase
{
    /**
     * Returns a clause for testing, optionally with the given field as constructor argument.
     */
    abstract protected function getTestClause(?string $constructorArgumentField = null): Clause;

    /**
     * Returns the rendered clause without field, operator and boost.
     */
    abstract protected function getExpectedBody(): string;

    public function testRendersBodyOnlyByDefault(): void
    {
        $this->assertSame($this->getExpectedBody(), (string) $this->getTestClause());
    }

    public function testRendersFieldSetBySetField(): void
    {
        $clause = $this->getTestClause()->setField('field');

        $this->assertSame('field:' . $this->getExpectedBody(), (string) $clause);
    }

    public function testRendersFieldSetByConstructor(): void
    {
        $this->assertSame('field:' . $this->getExpectedBody(), (string) $this->getTestClause('field'));
    }

    public function testEmptyFieldIsNotRendered(): void
    {
        $this->assertSame($this->getExpectedBody(), (string) $this->getTestClause(''));
    }

    public function testSetFieldOverwritesConstructorArgument(): void
    {
        $clause = $this->getTestClause('field')->setField('otherField');

        $this->assertSame('otherField:' . $this->getExpectedBody(), (string) $clause);
    }

    public function testSetFieldOverwritesFieldSetBefore(): void
    {
        $clause = $this->getTestClause()->setField('field')->setField('otherField');

        $this->assertSame('otherField:' . $this->getExpectedBody(), (string) $clause);
    }

    public function testSetFieldWithoutArgumentUnsetsField(): void
    {
        $clause = $this->getTestClause('field')->setField();

        $this->assertSame($this->getExpectedBody(), (string) $clause);
    }

    /**
     * @param callable(Clause): Clause $modify
     */
    #[DataProvider('provideFluentModifications')]
    public function testFluentMethodsReturnTheClauseItself(callable $modify): void
    {
        $clause = $this->getTestClause();

        $this->assertSame($clause, $modify($clause));
    }

    /** @return array<string, array{callable(Clause): Clause}> */
    public static function provideFluentModifications(): array
    {
        return [
            'setField'   => [static fn(Clause $clause): Clause => $clause->setField('field')],
            'optional'   => [static fn(Clause $clause): Clause => $clause->optional()],
            'required'   => [static fn(Clause $clause): Clause => $clause->required()],
            'prohibited' => [static fn(Clause $clause): Clause => $clause->prohibited()],
            'boost'      => [static fn(Clause $clause): Clause => $clause->boost(2)],
        ];
    }

    public function testOptionalRendersNoOperator(): void
    {
        $this->assertSame($this->getExpectedBody(), (string) $this->getTestClause()->optional());
    }

    public function testRequiredPrependsPlus(): void
    {
        $this->assertSame('+' . $this->getExpectedBody(), (string) $this->getTestClause()->required());
    }

    public function testProhibitedPrependsMinus(): void
    {
        $this->assertSame('-' . $this->getExpectedBody(), (string) $this->getTestClause()->prohibited());
    }

    /**
     * @param callable(Clause): Clause $firstOperators
     */
    #[DataProvider('provideOperatorSequences')]
    public function testLastOperatorWins(callable $firstOperators, string $expectedOperator): void
    {
        $clause = $firstOperators($this->getTestClause());

        $this->assertSame($expectedOperator . $this->getExpectedBody(), (string) $clause);
    }

    /** @return array<string, array{callable(Clause): Clause, string}> */
    public static function provideOperatorSequences(): array
    {
        return [
            'optional after required and prohibited' => [
                static fn(Clause $clause): Clause => $clause->required()->prohibited()->optional(),
                '',
            ],
            'required after optional and prohibited' => [
                static fn(Clause $clause): Clause => $clause->optional()->prohibited()->required(),
                '+',
            ],
            'prohibited after optional and required' => [
                static fn(Clause $clause): Clause => $clause->optional()->required()->prohibited(),
                '-',
            ],
        ];
    }

    #[DataProvider('provideBoosts')]
    public function testBoostIsAppendedToTheClause(float $boost, string $expectedSuffix): void
    {
        $clause = $this->getTestClause()->boost($boost);

        $this->assertSame($this->getExpectedBody() . $expectedSuffix, (string) $clause);
    }

    /** @return array<string, array{float, string}> */
    public static function provideBoosts(): array
    {
        return [
            'default boost 1.0 is not rendered' => [1.0, ''],
            'integer value'                     => [2.0, '^2'],
            'decimal value'                     => [2.5, '^2.5'],
            'trailing decimal zeros are cut'    => [2.10, '^2.1'],
            'value lower than 1'                => [0.5, '^0.5'],
        ];
    }

    public function testLastBoostWins(): void
    {
        $clause = $this->getTestClause()->boost(3)->boost(1.5);

        $this->assertSame($this->getExpectedBody() . '^1.5', (string) $clause);
    }

    public function testBoostThrowsExceptionForInvalidValue(): void
    {
        $this->expectException(InvalidArgumentException::class);

        $this->getTestClause()->boost(0);
    }

    public function testFieldOperatorAndBoostAreRenderedInThisOrder(): void
    {
        $clause = $this->getTestClause()
            ->setField('field')
            ->required()
            ->boost(2);

        $this->assertSame('+field:' . $this->getExpectedBody() . '^2', (string) $clause);
    }
}
