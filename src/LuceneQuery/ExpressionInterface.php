<?php

namespace LuceneQuery;

interface ExpressionInterface
{
    /**
     * Returns the query as string.
     *
     * @return string
     */
    public function __toString(): string;
}
