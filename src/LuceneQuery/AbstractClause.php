<?php

namespace LuceneQuery;

/**
 * An abstract clause
 */
abstract class AbstractClause implements Clause
{
    /**
     * Separator between field name and clause
     *
     * @var string
     */
    private const FIELD_SEPARATOR = ':';

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
     * Specifies a field to search in.
     *
     * @param string $name A field name
     *
     * @return self
     */
    public function setField(string $name = Field::DEFAULT): self
    {
        $this->field = new Field($name);

        return $this;
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
            : (string) $this->field . self::FIELD_SEPARATOR;
    }
}
