<?php

declare(strict_types=1);

namespace AskLucy\Expression\Clause;

use AskLucy\Escape;
use AskLucy\Exception\InvalidArgumentException;
use AskLucy\Expression\Field;
use AskLucy\Expression\Fuzziness;

/**
 * A single word. Special characters are escaped automatically.
 */
final class Term extends AbstractClause
{
    private readonly string $searchString;

    private Fuzziness $fuzziness = Fuzziness::None;

    public function __construct(string $searchString, string $field = Field::DEFAULT)
    {
        parent::__construct($field);

        $searchString = Escape::trim($searchString);

        if ('' === $searchString) {
            throw new InvalidArgumentException('A term must not be empty.');
        }

        if (Escape::containsWhitespace($searchString)) {
            throw new InvalidArgumentException('A term must not contain whitespace.');
        }

        $this->searchString = $searchString;
    }

    /**
     * Makes the term fuzzy. Values lower than 1 disable fuzziness, values greater than 2 are limited to 2.
     */
    public function fuzzify(int $distance = 2): self
    {
        return match (true) {
            $distance < 1 => $this->fuzzify0(),
            1 === $distance => $this->fuzzify1(),
            default => $this->fuzzify2(),
        };
    }

    public function fuzzify0(): self
    {
        $this->fuzziness = Fuzziness::None;

        return $this;
    }

    public function fuzzify1(): self
    {
        $this->fuzziness = Fuzziness::One;

        return $this;
    }

    public function fuzzify2(): self
    {
        $this->fuzziness = Fuzziness::Two;

        return $this;
    }

    protected function body(): string
    {
        return Escape::term($this->searchString) . $this->fuzziness->suffix();
    }
}
