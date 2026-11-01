<?php

declare(strict_types=1);

namespace AskLucy\Expression;

/**
 * The boolean operators connecting the clauses of a query.
 */
enum Conjunction: string
{
    case And = 'AND';
    case Or  = 'OR';
    case Not = 'NOT';
}
