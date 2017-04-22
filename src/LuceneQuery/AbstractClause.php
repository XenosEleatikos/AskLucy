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
     * Returns the field specification.
     *
     * @return string
     */
    protected function getFieldSpecification(): string
    {
        $term = (empty((string)$this->field))
            ? Field::DEFAULT
            : (string)$this->field . ':';

        return $term;
    }

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
