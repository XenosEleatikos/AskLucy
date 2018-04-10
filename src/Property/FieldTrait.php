<?php
namespace AskLucy\Property;

use AskLucy\Expression\Field;

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
}
