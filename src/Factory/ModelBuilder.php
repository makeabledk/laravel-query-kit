<?php

namespace Makeable\QueryKit\Factory;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Arr;

class ModelBuilder
{
    /**
     * @var Model
     */
    protected $model;

    /**
     * @var array
     */
    protected $attributes;

    /**
     * @param Model $model
     */
    public function __construct($model)
    {
        $this->model = $model;
        $this->attributes = [];
    }


    public function makeAttributes()
    {

    }

    /**
     * @param array $attributes
     * @return ModelBuilder
     */
    public function mergeAttributes($attributes)
    {
        foreach (Arr::wrap($attributes) as $attribute)
        {
            isset($this->attributes[$attribute->getName()])
                ? $this->attributes[$attribute->getName()]->merge($attribute)
                : $this->attributes[$attribute->getName()] = $attribute;
        }

        return $this;
    }
}