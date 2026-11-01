<?php

declare(strict_types=1);

namespace AskLucy\Expression\Clause;

use AskLucy\Expression\Boost;
use AskLucy\Expression\Field;
use AskLucy\Expression\Operator;

/**
 * Base class of all clauses. It renders the parts common to all clauses (operator, field and boost) around the body
 * provided by the concrete clause.
 */
abstract class AbstractClause implements Clause
{
    private Field $field;

    private Operator $operator = Operator::Optional;

    private ?Boost $boost = null;

    public function __construct(string $field = Field::DEFAULT)
    {
        $this->field = new Field($field);
    }

    public function setField(string $name = Field::DEFAULT): static
    {
        $this->field = new Field($name);

        return $this;
    }

    public function optional(): static
    {
        $this->operator = Operator::Optional;

        return $this;
    }

    public function required(): static
    {
        $this->operator = Operator::Required;

        return $this;
    }

    public function prohibited(): static
    {
        $this->operator = Operator::Prohibited;

        return $this;
    }

    public function boost(float $value): static
    {
        $this->boost = new Boost($value);

        return $this;
    }

    public function __toString(): string
    {
        $body = $this->body();

        return '' === $body ? '' : $this->decorate($body);
    }

    /**
     * Renders the clause without operator, field and boost.
     */
    abstract protected function body(): string;

    /**
     * Whether an operator, a field or a boost is set.
     */
    protected function isDecorated(): bool
    {
        return Operator::Optional !== $this->operator
            || '' !== (string) $this->field
            || null !== $this->boost;
    }

    /**
     * Adds operator, field and boost to the given body.
     */
    protected function decorate(string $body): string
    {
        return $this->operator->value . $this->field . $body . $this->boost;
    }
}
