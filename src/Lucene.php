<?php

declare(strict_types=1);

namespace AskLucy;

use AskLucy\Expression\Clause\Phrase;
use AskLucy\Expression\Clause\Query;
use AskLucy\Expression\Clause\Range;
use AskLucy\Expression\Clause\Regex;
use AskLucy\Expression\Clause\Term;
use AskLucy\Expression\Clause\Wildcard;
use AskLucy\Expression\Field;

/**
 * Factory for the clauses of a Lucene query.
 */
final class Lucene
{
    /**
     * Creates a phrase matching a sequence of words.
     */
    public static function phrase(string $searchPhrase, string $field = Field::DEFAULT): Phrase
    {
        return new Phrase($searchPhrase, $field);
    }

    /**
     * Creates a term matching a single word.
     */
    public static function term(string $searchString, string $field = Field::DEFAULT): Term
    {
        return new Term($searchString, $field);
    }

    /**
     * Creates a term containing the wildcards "?" and "*".
     */
    public static function wildcard(string $pattern, string $field = Field::DEFAULT): Wildcard
    {
        return new Wildcard($pattern, $field);
    }

    /**
     * Creates a regular expression clause.
     */
    public static function regex(string $pattern, string $field = Field::DEFAULT): Regex
    {
        return new Regex($pattern, $field);
    }

    /**
     * Creates a complex query that may contain any number of clauses.
     */
    public static function query(string $field = Field::DEFAULT): Query
    {
        return new Query($field);
    }

    /**
     * Creates a range. A bound given as null is open.
     */
    public static function range(?string $from, ?string $to, string $field = Field::DEFAULT): Range
    {
        return new Range($from, $to, $field);
    }

    /**
     * Escapes a string, so that all Lucene special characters and whitespace in it are matched literally. The result
     * is always a single token.
     */
    public static function escape(string $string): string
    {
        return Escape::literal($string);
    }
}
