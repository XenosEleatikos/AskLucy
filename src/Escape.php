<?php

declare(strict_types=1);

namespace AskLucy;

use AskLucy\Exception\InvalidArgumentException;

/**
 * Escapes user input for the usage in Lucene queries.
 *
 * Lucene's special characters are + - && || ! ( ) { } [ ] ^ " ~ * ? : \ /
 */
final class Escape
{
    private const SPECIAL_CHARACTERS = '+\-!(){}\[\]^"~*?:\\\\\/&|';

    /** Whitespace as Lucene sees it, including the ideographic space, and NUL bytes. */
    private const WHITESPACE = '[\s\p{Z}\x00]';

    private const RESERVED_WORDS = ['AND', 'OR', 'NOT', 'TO'];

    /**
     * Escapes a single search term, so that it is never interpreted as operator or syntax element.
     */
    public static function term(string $term): string
    {
        if (in_array($term, self::RESERVED_WORDS, true)) {
            return '\\' . $term;
        }

        return (string) preg_replace('/[' . self::SPECIAL_CHARACTERS . ']/', '\\\\$0', $term);
    }

    /**
     * Escapes a string that may contain whitespace, so that it is matched literally as a single token.
     *
     * @throws InvalidArgumentException if the string is not valid UTF-8
     */
    public static function literal(string $string): string
    {
        $escaped = preg_replace('/' . self::WHITESPACE . '/u', '\\\\$0', self::term($string));

        return $escaped ?? throw new InvalidArgumentException('The given string is not valid UTF-8.');
    }

    /**
     * Escapes a field name. In contrast to terms, field names may contain whitespace.
     */
    public static function field(string $field): string
    {
        return self::literal($field);
    }

    /**
     * Strips whitespace (as defined by {@see containsWhitespace()}) from the beginning and the end of the string.
     *
     * @throws InvalidArgumentException if the string is not valid UTF-8
     */
    public static function trim(string $string): string
    {
        $trimmed = preg_replace('/^' . self::WHITESPACE . '+|' . self::WHITESPACE . '+\\z/u', '', $string);

        return $trimmed ?? throw new InvalidArgumentException('The given string is not valid UTF-8.');
    }

    /**
     * Splits the string into words at whitespace (as defined by {@see containsWhitespace()}).
     *
     * @return list<string>
     *
     * @throws InvalidArgumentException if the string is not valid UTF-8
     */
    public static function words(string $string): array
    {
        $words = preg_split('/' . self::WHITESPACE . '+/u', $string, -1, PREG_SPLIT_NO_EMPTY);

        return false === $words
            ? throw new InvalidArgumentException('The given string is not valid UTF-8.')
            : $words;
    }

    /**
     * Tells if the string contains whitespace, including the ideographic space, which Lucene treats as such.
     *
     * @throws InvalidArgumentException if the string is not valid UTF-8
     */
    public static function containsWhitespace(string $string): bool
    {
        $result = preg_match('/' . self::WHITESPACE . '/u', $string);

        if (false === $result) {
            throw new InvalidArgumentException('The given string is not valid UTF-8.');
        }

        return 1 === $result;
    }

    /**
     * Escapes the content of a phrase. Within quotation marks, only quotation marks and backslashes are special.
     */
    public static function phrase(string $phrase): string
    {
        return (string) preg_replace('/["\\\\]/', '\\\\$0', $phrase);
    }

    /**
     * Escapes a wildcard pattern. The wildcards * and ? are preserved, all other special characters are escaped.
     */
    public static function wildcard(string $pattern): string
    {
        if (in_array($pattern, self::RESERVED_WORDS, true)) {
            return '\\' . $pattern;
        }

        return (string) preg_replace('/[+\-!(){}\[\]^"~:\\\\\/&|]/', '\\\\$0', $pattern);
    }

    /**
     * Escapes the delimiter "/" in a regular expression, leaving already escaped characters untouched.
     */
    public static function regex(string $pattern): string
    {
        $escaped = '';
        $length  = strlen($pattern);

        for ($i = 0; $i < $length; $i++) {
            $character = $pattern[$i];

            if ('\\' === $character) {
                $escaped .= $character . ($pattern[++$i] ?? '');
            } elseif ('/' === $character) {
                $escaped .= '\\' . $character;
            } else {
                $escaped .= $character;
            }
        }

        return $escaped;
    }
}
