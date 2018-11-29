<?php
namespace AskLucy\Expression\Clause;

use AskLucy\Expression\Expression;
use AskLucy\Expression\Field;

/**
 * A clause
 */
interface Clause extends Expression
{
    /**
     * Sets the field.
     *
     * @param string $name The name of the field to search in
     *
     * @return self|static
     */
    public function setField(string $name = Field::DEFAULT): Clause;

    /**
     * Sets the optional operator.
     *
     * @return self|static
     */
    public function optional(): Clause;

    /**
     * Sets the required operator.
     *
     * @return self|static
     */
    public function required(): Clause;

    /**
     * Sets the prohibited operator.
     *
     * @return self|static
     */
    public function prohibited(): Clause;

    /**
     * Boosts the clause.
     *
     * @param float $value The boost value
     *
     * @return self|static
     */
    public function boost(float $value): Clause;
}
