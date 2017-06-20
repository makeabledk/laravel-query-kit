<?php

namespace Makeable\QueryKit\Builder;

use Makeable\QueryKit\Contracts\QueryConstraint;

class WhereNull implements QueryConstraint
{
    /**
     * @var mixed
     */
    private $property;

    /**
     * WhereNull constructor.
     *
     * @param $property
     */
    public function __construct($property)
    {
        $this->property = $property;
    }

    /**
     * @param $model
     *
     * @return bool
     */
    public function check($model)
    {
        return $model->{$this->property} === null;
    }
}
