<?php

namespace LuceneQuery;

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
     * @return void
     */
    public function setField(string $name = Field::DEFAULT): void;

    /**
     * Sets the optional operator.
     *
     * @return void
     */
    public function optional(): void;

    /**
     * Sets the required operator.
     *
     * @return void
     */
    public function required(): void;

    /**
     * Sets the prohibited operator.
     *
     * @return void
     */
    public function prohibited(): void;
}
