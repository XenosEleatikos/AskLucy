<?php
namespace AskLucy\Property;

use AskLucy\Expression\Clause\Clause;
use AskLucy\Expression\Operator;

/**
 * Provides accessors for an operator.
 */
trait OperatorTrait
{
    /**
     * The operator
     *
     * @var Operator
     */
    private $operator;

    /**
     * Sets the optional operator.
     *
     * @return self|static
     */
    public function optional(): Clause
    {
        $this->operator = Operator::optional();

        return $this;
    }

    /**
     * Sets the required operator.
     *
     * @return self|static
     */
    public function required(): Clause
    {
        $this->operator = Operator::required();

        return $this;
    }

    /**
     * Sets the prohibited operator.
     *
     * @return self|static
     */
    public function prohibited(): Clause
    {
        $this->operator = Operator::prohibited();

        return $this;
    }
}
