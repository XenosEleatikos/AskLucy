<?php

namespace LuceneQuery;

abstract class AbstractClause implements Clause
{
    /**
     * The field
     *
     * @var Field
     */
    protected $field;

    /**
     * Adds a field to search in.
     *
     * @param string $name A field name
     */
    public function addField(string $name = ''): void
    {
        $this->field = new Field($name);
    }
}
