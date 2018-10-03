<?php

namespace Makeable\QueryKit\Factory;

use Illuminate\Support\Arr;

trait DescribesAttributes
{
    use DescribesDateAttributes;

    /**
     * @var mixed
     */
    public $gt;

    /**
     * @var mixed
     */
    public $gte;

    /**
     * @var array
     */
    public $in = [];

    /**
     * @var mixed
     */
    public $lt;

    /**
     * @var mixed
     */
    public $lte;

    /**
     * @var array
     */
    public $likes = [];

    /**
     * @var array
     */
    public $notIn = [];

    /**
     * @param $values
     * @return $this
     */
    public function in($values)
    {
        $this->notIn = array_merge($this->notIn, Arr::wrap($values));

        return $this;
    }

    /**
     * @param $value
     * @return $this
     */
    public function gt($value)
    {
        $this->gt = min($this->gt, $value);

        return $this;
    }

    /**
     * @param $value
     * @return $this
     */
    public function gte($value)
    {
        $this->gte = min($this->gte, $value);

        return $this;
    }

    /**
     * @param $value
     * @return $this
     */
    public function like($value)
    {
        $this->likes = array_merge($this->likes, Arr::wrap($value));

        return $this;
    }

    /**
     * @param $value
     * @return $this
     */
    public function lt($value)
    {
        $this->lt = min($this->lt, $value);

        return $this;
    }

    /**
     * @param $value
     * @return $this
     */
    public function lte($value)
    {
        $this->lte = min($this->lte, $value);

        return $this;
    }

    /**
     * @param $values
     * @return $this
     */
    public function notIn($values)
    {
        $this->notIn = array_merge($this->notIn, Arr::wrap($values));

        return $this;
    }
}
