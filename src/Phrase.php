<?php
namespace AskLucy;

use AskLucy\Property\BoostTrait;
use AskLucy\Property\FieldTrait;
use AskLucy\Property\OperatorTrait;

/**
 * A phrase
 */
class Phrase implements Clause
{
    use BoostTrait;
    use FieldTrait;
    use OperatorTrait;

    /**
     * Separator between terms
     *
     * @var string
     */
    const TERM_SEPARATOR = ' ';

    /**
     * A list of terms
     *
     * @var Term[]
     */
    private $terms = [];

    /**
     * The required word proximity
     *
     * @var Proximity
     */
    private $proximity;

    /**
     * Constructs a phrase.
     *
     * @param string $searchPhrase The phrase to search for
     * @param string $field        Optional name of the field to search in
     */
    public function __construct(string $searchPhrase, string $field = Field::DEFAULT)
    {
        $this->addTerms($searchPhrase);

        $this->field     = new Field($field);
        $this->proximity = new Proximity;
    }

    /**
     * Sets the required word proximity.
     *
     * @param int $proximity The required proximity between the terms
     *
     * @return self
     */
    public function setProximity(int $proximity = 0): self
    {
        $this->proximity = new Proximity($proximity);

        return $this;
    }

    /**
     * Returns the phrase as string.
     *
     * @return string
     */
    public function __toString(): string
    {
        $terms = implode(self::TERM_SEPARATOR, $this->terms);
        $terms = (count($this->terms) > 1)
            ? '"' . $terms . '"'
            : $terms;

        return $this->operator
            . $this->field
            . $terms
            . $this->getProximitySpecification()
            . $this->boost;
    }

    /**
     * Creates a term object for each word of a given phrase and appends it to the list.
     *
     * @param string $searchPhrase A search phrase
     *
     * @return void
     */
    private function addTerms(string $searchPhrase): void
    {
        $this->terms = array_merge(
            $this->terms,
            array_map(
                function (string $searchTerm)
                {
                    return new Term($searchTerm);
                },
                explode(self::TERM_SEPARATOR, $searchPhrase)
            )
        );
    }

    /**
     * Term proximity only makes sense for more than one term, so we can ignore it for a single term. Actually we have
     * to ignore it for don't getting confused with the fuzziness operator for single terms.
     *
     * @see Fuzziness
     *
     * @return string
     */
    private function getProximitySpecification(): string
    {
        return (count($this->terms) > 1)
            ? $this->proximity
            : '';
    }
}
