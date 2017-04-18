<?php

namespace LuceneQuery;

interface QueryInterface
{
    /**
     * Returns the query as string.
     *
     * @return string
     */
    public function __toString(): string;
}
