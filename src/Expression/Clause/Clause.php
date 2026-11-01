<?php

declare(strict_types=1);

namespace AskLucy\Expression\Clause;

use AskLucy\Expression\Expression;
use AskLucy\Expression\Field;

interface Clause extends Expression
{
    /**
     * Sets the field to search in. Calling it without argument resets to the default field.
     */
    public function setField(string $name = Field::DEFAULT): static;

    /**
     * Matching is optional (default).
     */
    public function optional(): static;

    /**
     * Matching is required.
     */
    public function required(): static;

    /**
     * Matching is prohibited.
     */
    public function prohibited(): static;

    /**
     * Sets the relevance boost factor, which must be greater than zero.
     */
    public function boost(float $value): static;
}
