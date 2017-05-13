<?php

namespace LuceneQuery\Property;

use LuceneQuery\Field;

/**
 * Provides accessors for the field.
 */
trait FieldTrait
{
    /**
     * The operator
     *
     * @var Field
     */
    private $field;

    /**
     * Sets the field.
     *
     * @param string $name The name of the field to search in
     *
     * @return void
     */
    public function setField(string $name = Field::DEFAULT): void
    {
        $this->field = new Field($name);
    }

    /**
     * Returns the field specification.
     *
     * @return string
     */
    protected function getFieldSpecification(): string
    {
        return (empty((string) $this->field))
            ? Field::DEFAULT
            : (string) $this->field . Field::FIELD_SEPARATOR;
    }
}
