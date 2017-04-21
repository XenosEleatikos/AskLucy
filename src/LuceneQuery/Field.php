<?php

namespace LuceneQuery;

/**
 * A field
 */
class Field
{
    /**
     * @var string
     */
    private $name;

    /**
     * Constructs a field.
     *
     * @param string $name The name of the field
     */
    public function __construct(string $name = '')
    {
        $this->name = $name;
    }

    /**
     * Returns the name of the field.
     *
     * @return string
     */
    public function __toString(): string
    {
        return $this->name;
    }
}
