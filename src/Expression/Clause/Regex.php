<?php

declare(strict_types=1);

namespace AskLucy\Expression\Clause;

use AskLucy\Escape;
use AskLucy\Exception\InvalidArgumentException;
use AskLucy\Expression\Field;

/**
 * A regular expression, rendered between slashes. Slashes within the pattern are escaped automatically.
 */
final class Regex extends AbstractClause
{
    public function __construct(private readonly string $pattern, string $field = Field::DEFAULT)
    {
        parent::__construct($field);

        if ('' === $pattern) {
            throw new InvalidArgumentException('A regular expression must not be empty.');
        }

        if (str_ends_with($pattern, '\\')) {
            throw new InvalidArgumentException(
                'A regular expression must not end with a backslash. Use the character class [\\\\] to match one.'
            );
        }
    }

    protected function body(): string
    {
        return '/' . Escape::regex($this->pattern) . '/';
    }
}
