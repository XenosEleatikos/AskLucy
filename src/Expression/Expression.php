<?php

declare(strict_types=1);

namespace AskLucy\Expression;

/**
 * An expression is a part of a Lucene query that can be rendered to a string.
 */
interface Expression extends \Stringable {}
