<?php

declare(strict_types=1);

namespace AskLucy\Expression\Clause;

use AskLucy\Escape;
use AskLucy\Exception\InvalidArgumentException;
use AskLucy\Expression\Field;

/**
 * A range between a lower and an upper bound. Bounds containing whitespace or closing brackets are quoted, because
 * Lucene doesn't recognize escaped brackets within a range. A bound given as null is open and rendered as "*". Ranges are inclusive
 * of both bounds by default.
 */
final class Range extends AbstractClause
{
    private const OPEN_BOUND = '*';

    private readonly ?string $from;

    private readonly ?string $to;

    private bool $includesFrom = true;

    private bool $includesTo = true;

    public function __construct(?string $from, ?string $to, string $field = Field::DEFAULT)
    {
        parent::__construct($field);

        $this->from = $this->normalize($from);
        $this->to   = $this->normalize($to);
    }

    /**
     * Includes both bounds: [a TO b]
     */
    public function inclusive(): self
    {
        $this->includesFrom = true;
        $this->includesTo   = true;

        return $this;
    }

    /**
     * Excludes both bounds: {a TO b}
     */
    public function exclusive(): self
    {
        $this->includesFrom = false;
        $this->includesTo   = false;

        return $this;
    }

    public function inclusiveFrom(): self
    {
        $this->includesFrom = true;

        return $this;
    }

    public function exclusiveFrom(): self
    {
        $this->includesFrom = false;

        return $this;
    }

    public function inclusiveTo(): self
    {
        $this->includesTo = true;

        return $this;
    }

    public function exclusiveTo(): self
    {
        $this->includesTo = false;

        return $this;
    }

    protected function body(): string
    {
        return ($this->includesFrom ? '[' : '{')
            . $this->render($this->from)
            . ' TO '
            . $this->render($this->to)
            . ($this->includesTo ? ']' : '}');
    }

    private function normalize(?string $bound): ?string
    {
        if (null === $bound) {
            return null;
        }

        $bound = Escape::trim($bound);

        if ('' === $bound) {
            throw new InvalidArgumentException('A bound of a range must not be empty. Use null for an open bound.');
        }

        if ($this->needsQuotes($bound) && str_ends_with($bound, '\\')) {
            // Lucene reads the backslash and the closing quotation mark as an escaped quotation mark.
            throw new InvalidArgumentException(
                'A bound of a range containing whitespace or closing brackets must not end with a backslash.'
            );
        }

        return $bound;
    }

    private function needsQuotes(string $bound): bool
    {
        return Escape::containsWhitespace($bound) || 1 === preg_match('/[\]}]/', $bound);
    }

    private function render(?string $bound): string
    {
        return match (true) {
            null === $bound => self::OPEN_BOUND,
            $this->needsQuotes($bound) => '"' . Escape::phrase($bound) . '"',
            default => Escape::term($bound),
        };
    }
}
