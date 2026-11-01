<?php

declare(strict_types=1);

namespace AskLucy\Expression\Clause;

use AskLucy\Escape;
use AskLucy\Exception\InvalidArgumentException;
use AskLucy\Expression\Field;
use AskLucy\Expression\Proximity;

/**
 * A sequence of words. It is always rendered in quotation marks.
 */
final class Phrase extends AbstractClause
{
    /** @var list<string> */
    private readonly array $words;

    private Proximity $proximity;

    public function __construct(string $searchPhrase, string $field = Field::DEFAULT)
    {
        parent::__construct($field);

        $words = Escape::words($searchPhrase);

        if ([] === $words) {
            throw new InvalidArgumentException('A phrase must not be empty.');
        }

        $this->words     = $words;
        $this->proximity = new Proximity();
    }

    /**
     * Sets the maximum distance between the words. 0 means exact matching.
     */
    public function setProximity(int $proximity = 0): self
    {
        $this->proximity = new Proximity($proximity);

        return $this;
    }

    protected function body(): string
    {
        return '"' . Escape::phrase(implode(' ', $this->words)) . '"'
            . (count($this->words) > 1 ? $this->proximity : '');
    }
}
