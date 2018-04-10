<?php
namespace AskLucy\Expression;

/**
 * An expression
 */
interface Expression
{
    /**
     * Returns a string representation of the object.
     *
     * @return string
     */
    public function __toString(): string;
}
