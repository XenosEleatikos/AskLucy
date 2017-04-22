<?php

namespace LuceneQuery;

interface Clause
{
    /**
     * Returns the query as string.
     *
     * @return string
     */
    public function __toString(): string;
}
