<?php
namespace AskLucy\Property;

use AskLucy\Expression\Clause\Clause;
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
     * @return self|static
     */
    public function setField(string $name = Field::DEFAULT): Clause
    {
        $this->field = new Field($name);

        return $this;
    }
}
