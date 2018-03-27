<?php
namespace AskLucy\Property;

use AskLucy\Operator;

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
     * @return void
     */
    public function optional(): void
    {
        $this->operator = new Operator(Operator::SYMBOL_OPTIONAL);
    }

    /**
     * Sets the required operator.
     *
     * @return void
     */
    public function required(): void
    {
        $this->operator = new Operator(Operator::SYMBOL_REQUIRED);
    }

    /**
     * Sets the prohibited operator.
     *
     * @return void
     */
    public function prohibited(): void
    {
        $this->operator = new Operator(Operator::SYMBOL_PROHIBITED);
    }
}
