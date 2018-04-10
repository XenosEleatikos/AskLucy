<?php
namespace AskLucy\Property;

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
     * @return void
     */
    public function optional(): void
    {
        $this->operator = Operator::optional();
    }

    /**
     * Sets the required operator.
     *
     * @return void
     */
    public function required(): void
    {
        $this->operator = Operator::required();
    }

    /**
     * Sets the prohibited operator.
     *
     * @return void
     */
    public function prohibited(): void
    {
        $this->operator = Operator::prohibited();
    }
}
