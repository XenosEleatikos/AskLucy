<?php

declare(strict_types=1);

namespace AskLucy\Expression\Clause;

use AskLucy\Escape;
use AskLucy\Exception\InvalidArgumentException;
use AskLucy\Expression\Field;

/**
 * A single word containing the wildcards "?" (exactly one character) and "*" (zero or more characters). All other
 * special characters are escaped automatically.
 */
final class Wildcard extends AbstractClause
{
    private readonly string $pattern;

    public function __construct(string $pattern, string $field = Field::DEFAULT)
    {
        parent::__construct($field);

        $pattern = Escape::trim($pattern);

        if ('' === $pattern) {
            throw new InvalidArgumentException('A wildcard pattern must not be empty.');
        }

        if (Escape::containsWhitespace($pattern)) {
            throw new InvalidArgumentException('A wildcard pattern must not contain whitespace.');
        }

        $this->pattern = $pattern;
    }

    protected function body(): string
    {
        return Escape::wildcard($this->pattern);
    }
}
