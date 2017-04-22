<?php

namespace LuceneQuery;

interface Expression
{
    /**
     * Returns the query as string.
     *
     * @return string
     */
    public function __toString(): string;
}
