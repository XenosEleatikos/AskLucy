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
     * Sets the field specification to default.
     */
    protected function __construct()
    {
        $this->setField(new Field);
    }

    /**
     * Returns the field specification.
     *
     * @return string
     */
    protected function getFieldSpecification(): string
    {
        $term = (empty((string) $this->field))
            ? Field::DEFAULT
            : (string) $this->field . ':';

        return $term;
    }

    /**
     * Specifies a field to search in.
     *
     * @param string $name A field name
     */
    public function setField(string $name = ''): void
    {
        $this->field = new Field($name);
    }
}
