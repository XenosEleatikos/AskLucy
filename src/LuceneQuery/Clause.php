<?php

namespace LuceneQuery;

/**
 * A clause
 */
interface Clause extends Expression
{
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
