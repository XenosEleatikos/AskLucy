<?php

declare(strict_types=1);

namespace AskLucy\Expression;

/**
 * The maximum Damerau-Levenshtein distance of a fuzzy search term.
 */
enum Fuzziness: int
{
    case None = 0;
    case One  = 1;
    case Two  = 2;

    private const OPERATOR = '~';

    /**
     * Returns the suffix to be appended to the term. Lucene's default distance is 2, so it needs no number.
     */
    public function suffix(): string
    {
        return match ($this) {
            self::None => '',
            self::One  => self::OPERATOR . '1',
            self::Two  => self::OPERATOR,
        };
    }
}
