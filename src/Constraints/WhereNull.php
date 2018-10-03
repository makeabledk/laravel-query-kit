<?php

namespace Makeable\QueryKit\Constraints;

class WhereNull implements ConstraintContract
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
