<?php

declare(strict_types=1);

namespace AskLucy\Expression;

/**
 * The prefix operator of a clause, defining if matching is optional, required or prohibited.
 */
enum Operator: string
{
    case Optional   = '';
    case Required   = '+';
    case Prohibited = '-';
}
