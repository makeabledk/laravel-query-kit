<?php

namespace Makeable\QueryKit\Builder;

use BadMethodCallException;
use Makeable\QueryKit\Contracts\QueryConstraint;

class WhereColumn implements QueryConstraint
{
    /**
     * @var mixed
     */
    protected $property;

    /**
     * @var mixed
     */
    protected $operator;

    /**
     * @var mixed
     */
    protected $propertyToCompare;

    /**
     * WhereColumn constructor.
     *
     * @param  mixed  $property
     * @param  mixed  $operator
     * @param  mixed  $propertyToCompare
     */
    public function __construct($property, $operator, $propertyToCompare)
    {
        $this->property = $property;
        $this->operator = $operator;
        $this->propertyToCompare = $propertyToCompare;
    }

    /**
     * @param  $model
     * @return bool
     */
    public function check($model)
    {
        if ($this->isSubQuery()) {
            return Builder::make($model, $this->property)->check() && Builder::make($model, $this->propertyToCompare)->check();
        }

        switch ($this->operator) {
            case '=':
                return $this->attribute($model, $this->property) === $this->attribute($model, $this->propertyToCompare);
                break;
            case '>=':
                return $this->attribute($model, $this->property) >= $this->attribute($model, $this->propertyToCompare);
                break;
            case '<=':
                return $this->attribute($model, $this->property) <= $this->attribute($model, $this->propertyToCompare);
                break;
            case '<>':
                return $this->attribute($model, $this->property) !== $this->attribute($model, $this->propertyToCompare);
                break;
            case '>':
                return $this->attribute($model, $this->property) > $this->attribute($model, $this->propertyToCompare);
                break;
            case '<':
                return $this->attribute($model, $this->property) < $this->attribute($model, $this->propertyToCompare);
                break;
        }
        throw new BadMethodCallException('Operator not supported: '.$this->operator);
    }

    /**
     * @return bool
     */
    protected function isSubQuery()
    {
        return $this->operator === null && is_callable($this->property) && is_callable($this->propertyToCompare);
    }

    /**
     * @param  $model
     * @return mixed
     */
    protected function attribute($model, $property)
    {
        return $model->{$property};
    }
}
