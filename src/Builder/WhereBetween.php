<?php

namespace Makeable\QueryKit\Builder;

use Makeable\QueryKit\Contracts\QueryConstraint;

class WhereBetween implements QueryConstraint
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
     * @return bool
     */
    public function check($model)
    {
        [$min, $max] = $this->range;

        return $model->{$this->property} >= $min && $model->{$this->property} <= $max;
    }
}
