<?php

namespace Makeable\QueryKit\Constraints;

use Makeable\QueryKit\Constraints\ConstraintContract;

class WhereBetween implements ConstraintContract
{
    /**
     * @var mixed
     */
    protected $property;

    /**
     * @var array
     */
    protected $range;

    /**
     * WhereNull constructor.
     *
     * @param $property
     * @param $range
     */
    public function __construct($property, $range)
    {
        if ($range instanceof \Illuminate\Support\Collection) {
            $range = $range->toArray();
        }
        $this->property = $property;
        $this->range = $range;
    }

    /**
     * @param $model
     *
     * @return bool
     */
    public function check($model)
    {
        list($min, $max) = $this->range;

        return $model->{$this->property} >= $min && $model->{$this->property} <= $max;
    }
}
