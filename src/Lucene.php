<?php
namespace AskLucy;

use AskLucy\Exception\InvalidArgumentException;
use AskLucy\Expression\Clause\Phrase;
use AskLucy\Expression\Clause\Query;
use AskLucy\Expression\Clause\Range;
use AskLucy\Expression\Clause\Term;
use AskLucy\Expression\Field;

/**
 * Ask Lucy's main class containing factory methods to build a Lucene search clause.
 */
class Lucene
{
    /**
     * Makes and returns a search phrase.
     *
     * @param string $searchPhrase The phrase to search for
     * @param string $field        Optional name of the field to search in
     *
     * @return Phrase
     */
    public static function phrase(string $searchPhrase, string $field = Field::DEFAULT): Phrase
    {
        return new Phrase($searchPhrase, $field);
    }

    /**
     * Makes and returns a search term.
     *
     * @param string $searchString The string to search for
     * @param string $field        Optional name of the field to search in
     *
     * @return Term
     *
     * @throws InvalidArgumentException Throws an exception, if the given string contains spaces.
     */
    public static function term(string $searchString, string $field = Field::DEFAULT): Term
    {
        return new Term($searchString, $field);
    }

    /**
     * Makes and returns a complex query.
     *
     * @param string $field Optional name of the field to search in
     *
     * @return Query
     */
    public static function query(string $field = Field::DEFAULT): Query
    {
        return new Query($field);
    }

    /**
     * Makes and returns a Lucene range query.
     *
     * @param string $from  The lower bound
     * @param string $to    The upper bound
     * @param string $field Optional name of the field to search in
     *
     * @return Range
     *
     * @throws InvalidArgumentException Throws an exception, if the given string contains spaces.
     */
    public static function range(string $from, string $to, string $field = Field::DEFAULT): Range
    {
        return new Range($from, $to, $field);
    }
}
