<?php

declare(strict_types=1);

namespace AskLucy\Expression\Clause;

use AskLucy\Exception\InvalidArgumentException;
use AskLucy\Expression\Conjunction;

/**
 * A complex query containing any number of clauses, which may be queries themselves.
 */
final class Query extends AbstractClause
{
    private const CLAUSE_SEPARATOR = ' ';

    /** @var list<array{0: ?Conjunction, 1: Clause}> */
    private array $entries = [];

    public function add(Clause $clause): self
    {
        $this->assertNoCycle($clause);

        $this->entries[] = [null, $clause];

        return $this;
    }

    public function shouldHave(Clause $clause): self
    {
        return $this->add($clause->optional());
    }

    public function mustHave(Clause $clause): self
    {
        return $this->add($clause->required());
    }

    public function mustNotHave(Clause $clause): self
    {
        return $this->add($clause->prohibited());
    }

    /**
     * Adds a clause connected to the preceding one by "AND".
     */
    public function and(Clause $clause): self
    {
        $this->assertNoCycle($clause);

        $this->entries[] = [Conjunction::And, $clause];

        return $this;
    }

    /**
     * Adds a clause connected to the preceding one by "OR".
     */
    public function or(Clause $clause): self
    {
        $this->assertNoCycle($clause);

        $this->entries[] = [Conjunction::Or, $clause];

        return $this;
    }

    /**
     * Adds a clause connected to the preceding one by "NOT".
     */
    public function not(Clause $clause): self
    {
        $this->assertNoCycle($clause);

        $this->entries[] = [Conjunction::Not, $clause];

        return $this;
    }

    private function assertNoCycle(Clause $clause): void
    {
        if ($clause === $this || ($clause instanceof self && $clause->contains($this))) {
            throw new InvalidArgumentException('A query must not contain itself.');
        }
    }

    /**
     * Renders the query as part of another query. Then it must be enclosed in parentheses if it contains more than one
     * clause, otherwise the boolean operators of the outer query would apply to the single clauses.
     */
    private function renderNested(): string
    {
        $body = $this->renderBody(true);

        return '' === $body ? '' : $this->decorate($body);
    }

    private function contains(self $query): bool
    {
        foreach ($this->entries as [, $clause]) {
            if ($clause === $query || ($clause instanceof self && $clause->contains($query))) {
                return true;
            }
        }

        return false;
    }

    protected function body(): string
    {
        return $this->renderBody(false);
    }

    private function renderBody(bool $nested): string
    {
        $parts    = [];
        $collides = false;

        foreach ($this->entries as [$conjunction, $clause]) {
            $rendered = $clause instanceof self ? $clause->renderNested() : $clause->__toString();

            if ('' === $rendered) {
                continue;
            }

            $isFirst = [] === $parts;

            // "NOT" is a modifier itself, so it must not be followed by "+" or "-".
            if (Conjunction::Not === $conjunction && 1 === preg_match('/^[+-]/', $rendered)) {
                $rendered = '(' . $rendered . ')';
            }

            $parts[] = null === $conjunction || ($isFirst && Conjunction::Not !== $conjunction)
                ? $rendered
                : $conjunction->value . ' ' . $rendered;

            $collides = Conjunction::Not === $conjunction || self::isDecoratedClause($clause);
        }

        $body = implode(self::CLAUSE_SEPARATOR, $parts);

        if (count($parts) > 1) {
            return $nested || $this->isDecorated() ? '(' . $body . ')' : $body;
        }

        // A single clause needs parentheses if it would otherwise collide with the operator, the field or the boost of
        // this query, e. g. "+-a", "title:body:a" or "a^2^3".
        return $collides && $this->isDecorated() ? '(' . $body . ')' : $body;
    }

    private static function isDecoratedClause(Clause $clause): bool
    {
        return !$clause instanceof AbstractClause || $clause->isDecorated();
    }
}
