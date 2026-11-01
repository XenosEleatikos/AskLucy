<?php

declare(strict_types=1);

namespace AskLucy\Test\Expression\Clause;

use AskLucy\Exception\InvalidArgumentException;
use AskLucy\Expression\Clause\Clause;
use AskLucy\Expression\Clause\Query;
use AskLucy\Lucene;
use PHPUnit\Framework\Attributes\DataProvider;

final class QueryTest extends ClauseTestCase
{
    public function testRendersClausesSeparatedBySpaces(): void
    {
        $query = Lucene::query()
            ->add(Lucene::term('a'))
            ->add(Lucene::term('b'))
            ->add(Lucene::term('c'));

        $this->assertSame('a b c', (string) $query);
    }

    public function testEmptyQueryIsEmpty(): void
    {
        $this->assertSame('', (string) Lucene::query());
    }

    public function testIgnoresEmptyNestedQueries(): void
    {
        $query = Lucene::query()
            ->add(Lucene::term('a'))
            ->add(Lucene::query());

        $this->assertSame('a', (string) $query);
    }

    public function testIgnoresEmptyNestedQueryBeforeOtherClauses(): void
    {
        $query = Lucene::query()
            ->add(Lucene::query())
            ->add(Lucene::term('a'));

        $this->assertSame('a', (string) $query);
    }

    public function testShouldHave(): void
    {
        $query = Lucene::query()->shouldHave(Lucene::term('a')->required());

        $this->assertSame('a', (string) $query);
    }

    public function testMustHave(): void
    {
        $this->assertSame('+a', (string) Lucene::query()->mustHave(Lucene::term('a')));
    }

    public function testMustNotHave(): void
    {
        $this->assertSame('-a', (string) Lucene::query()->mustNotHave(Lucene::term('a')));
    }

    public function testMixedOperators(): void
    {
        $query = Lucene::query()
            ->shouldHave(Lucene::term('word'))
            ->mustHave(Lucene::phrase('Lucene query'))
            ->mustNotHave(Lucene::phrase('Java development'));

        $this->assertSame('word +"Lucene query" -"Java development"', (string) $query);
    }

    public function testAddMethodsReturnTheQuery(): void
    {
        $query = Lucene::query();

        $this->assertSame($query, $query->add(Lucene::term('a')));
        $this->assertSame($query, $query->shouldHave(Lucene::term('a')));
        $this->assertSame($query, $query->mustHave(Lucene::term('a')));
        $this->assertSame($query, $query->mustNotHave(Lucene::term('a')));
        $this->assertSame($query, $query->and(Lucene::term('a')));
        $this->assertSame($query, $query->or(Lucene::term('a')));
        $this->assertSame($query, $query->not(Lucene::term('a')));
    }

    public function testBooleanOperators(): void
    {
        $query = Lucene::query()
            ->add(Lucene::term('a'))
            ->and(Lucene::term('b'))
            ->or(Lucene::term('c'))
            ->not(Lucene::term('d'));

        $this->assertSame('a AND b OR c NOT d', (string) $query);
    }

    public function testConjunctionBeforeFirstClauseIsOmitted(): void
    {
        $this->assertSame('a OR b', (string) Lucene::query()->and(Lucene::term('a'))->or(Lucene::term('b')));
    }

    public function testNotBeforeFirstClauseIsKept(): void
    {
        $this->assertSame('NOT a', (string) Lucene::query()->not(Lucene::term('a')));
    }

    public function testConjunctionBeforeEmptyClauseDoesNotLeaveDanglingOperator(): void
    {
        $query = Lucene::query()->add(Lucene::term('a'))->and(Lucene::query());

        $this->assertSame('a', (string) $query);
    }

    public function testNestedQueriesAreEnclosedInParentheses(): void
    {
        $query = Lucene::query()
            ->add(Lucene::term('a'))
            ->and(Lucene::query()->add(Lucene::term('b'))->or(Lucene::term('c')));

        $this->assertSame('a AND (b OR c)', (string) $query);
    }

    public function testNestedQueryWithSingleClauseIsNotEnclosedInParentheses(): void
    {
        $query = Lucene::query()
            ->add(Lucene::term('a'))
            ->and(Lucene::query()->add(Lucene::term('b')));

        $this->assertSame('a AND b', (string) $query);
    }

    #[DataProvider('provideDecoratedSingleClauseQueries')]
    public function testDecoratedQueryWithSingleDecoratedClauseIsEnclosedInParentheses(Query $query, string $expected): void
    {
        $this->assertSame($expected, (string) $query);
    }

    /** @return array<string, array{Query, string}> */
    public static function provideDecoratedSingleClauseQueries(): array
    {
        return [
            'operator and operator' => [
                Lucene::query()->required()->add(Lucene::term('x')->prohibited()),
                '+(-x)',
            ],
            'field and field' => [
                Lucene::query('title')->add(Lucene::term('x', 'body')),
                'title:(body:x)',
            ],
            'boost and boost' => [
                Lucene::query()->boost(3)->add(Lucene::term('x')->boost(2)),
                '(x^2)^3',
            ],
            'operator and NOT' => [
                Lucene::query()->required()->not(Lucene::term('x')),
                '+(NOT x)',
            ],
            'operator and decorated nested query' => [
                Lucene::query()->required()->add(Lucene::query()->prohibited()->add(Lucene::term('x'))),
                '+(-x)',
            ],
        ];
    }

    public function testDecoratedQueryWithSingleUndecoratedClauseNeedsNoParentheses(): void
    {
        $this->assertSame('+x', (string) Lucene::query()->required()->add(Lucene::term('x')));
        $this->assertSame('x^2', (string) Lucene::query()->boost(2)->add(Lucene::term('x')));
    }

    public function testDecoratedQueryWithSingleClauseNotImplementedByTheLibraryIsEnclosedInParentheses(): void
    {
        $clause = new class implements Clause {
            public function setField(string $name = ''): static
            {
                return $this;
            }

            public function optional(): static
            {
                return $this;
            }

            public function required(): static
            {
                return $this;
            }

            public function prohibited(): static
            {
                return $this;
            }

            public function boost(float $value): static
            {
                return $this;
            }

            public function __toString(): string
            {
                return 'custom';
            }
        };

        $this->assertSame('+(custom)', (string) Lucene::query()->required()->add($clause));
        $this->assertSame('custom', (string) Lucene::query()->add($clause));
    }

    public function testDecoratedEmptyQueryIsEmpty(): void
    {
        $this->assertSame('', (string) Lucene::query()->required());
        $this->assertSame('', (string) Lucene::query('title'));
        $this->assertSame('', (string) Lucene::query()->boost(2));
        $this->assertSame(
            'a',
            (string) Lucene::query()->add(Lucene::term('a'))->add(Lucene::query()->required())
        );
    }

    public function testNotIsFollowedByParenthesesIfTheClauseHasAnOperator(): void
    {
        $query = Lucene::query()
            ->add(Lucene::term('a'))
            ->not(Lucene::term('x')->required())
            ->not(Lucene::term('y')->prohibited());

        $this->assertSame('a NOT (+x) NOT (-y)', (string) $query);
    }

    public function testAndAndOrAcceptClausesWithOperators(): void
    {
        $query = Lucene::query()
            ->add(Lucene::term('a'))
            ->and(Lucene::term('x')->prohibited())
            ->or(Lucene::term('y')->required());

        $this->assertSame('a AND -x OR +y', (string) $query);
    }

    public function testThrowsExceptionIfQueryIsAddedToItself(): void
    {
        $query = Lucene::query();

        $this->expectException(InvalidArgumentException::class);

        $query->add($query);
    }

    public function testThrowsExceptionIfQueryWouldBecomeCyclic(): void
    {
        $inner = Lucene::query();
        $outer = Lucene::query()->add($inner);

        $this->expectException(InvalidArgumentException::class);

        $inner->and($outer);
    }

    public function testThrowsExceptionIfDeeplyNestedQueryWouldBecomeCyclic(): void
    {
        $innermost = Lucene::query();
        $outer     = Lucene::query()->add(Lucene::query()->add($innermost));

        $this->expectException(InvalidArgumentException::class);

        $innermost->or($outer);
    }

    public function testThrowsExceptionForCycleAddedByOr(): void
    {
        $inner = Lucene::query();
        $outer = Lucene::query()->add($inner);

        $this->expectException(InvalidArgumentException::class);

        $inner->or($outer);
    }

    public function testThrowsExceptionForCycleAddedByNot(): void
    {
        $inner = Lucene::query();
        $outer = Lucene::query()->add($inner);

        $this->expectException(InvalidArgumentException::class);

        $inner->not($outer);
    }

    public function testAcceptsNestedQueriesThatDoNotContainTheOuterQuery(): void
    {
        $outer = Lucene::query();
        $inner = Lucene::query()->add(Lucene::query()->add(Lucene::term('a')));

        $this->assertSame('b OR a', (string) $outer->add(Lucene::term('b'))->or($inner));
    }

    public function testNotDoesNotWrapClausesContainingHyphensOrPlusSigns(): void
    {
        $this->assertSame(
            'a NOT "b-c"',
            (string) Lucene::query()->add(Lucene::term('a'))->not(Lucene::phrase('b-c'))
        );
    }

    public function testAcceptsTheSameClauseInSeveralQueries(): void
    {
        $term = Lucene::term('a');

        $this->assertSame(
            'a OR a',
            (string) Lucene::query()->or(Lucene::query()->add($term))->or(Lucene::query()->add($term))
        );
    }

    public function testFieldIsAppliedToAllClauses(): void
    {
        $query = Lucene::query('title')
            ->add(Lucene::term('Lucene'))
            ->add(Lucene::term('Apache'));

        $this->assertSame('title:(Lucene Apache)', (string) $query);
    }

    public function testFieldOfSingleClauseNeedsNoParentheses(): void
    {
        $this->assertSame('title:a', (string) Lucene::query('title')->add(Lucene::term('a')));
    }

    public function testFieldOfSubClauseDoesNotAffectOthers(): void
    {
        $query = Lucene::query()
            ->add(Lucene::term('Lucene', 'title'))
            ->add(Lucene::term('Apache'));

        $this->assertSame('title:Lucene Apache', (string) $query);
    }

    public function testOperatorAppliesToAllClauses(): void
    {
        $query = Lucene::query()
            ->add(Lucene::term('Lucene'))
            ->add(Lucene::phrase('search query'))
            ->required();

        $this->assertSame('+(Lucene "search query")', (string) $query);
    }

    public function testBoostAppliesToAllClauses(): void
    {
        $query = Lucene::query()
            ->add(Lucene::term('a'))
            ->add(Lucene::term('b'))
            ->boost(2);

        $this->assertSame('(a b)^2', (string) $query);
    }

    public function testFieldOperatorAndBoostCombined(): void
    {
        $query = Lucene::query('title')
            ->add(Lucene::term('a'))
            ->add(Lucene::term('b'))
            ->prohibited()
            ->boost(1.5);

        $this->assertSame('-title:(a b)^1.5', (string) $query);
    }

    protected function getExpectedBody(): string
    {
        return 'a';
    }

    protected function getTestClause(?string $constructorArgumentField = null): Clause
    {
        $query = null === $constructorArgumentField
            ? Lucene::query()
            : Lucene::query($constructorArgumentField);

        return $query->add(Lucene::term('a'));
    }
}
