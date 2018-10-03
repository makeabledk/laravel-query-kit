<?php

namespace Makeable\QueryKit\Factory;

use Illuminate\Database\Eloquent\Model;
use Makeable\QueryKit\Constraints\LikeInterpreter;
use Makeable\QueryKit\Factory\Generators\AttributeReflection;

class ModelAttribute
{
    use DescribesAttributes;

    /**
     * @var string
     */
    protected $name;

    /**
     * @var Model
     */
    protected $model;

    /**
     * @param $name
     * @param Model $model
     */
    public function __construct($name, $model)
    {
        $this->name = $name;
        $this->model = $model;
    }

    /**
     * @return Model
     */
    public function getModel()
    {
        return $this->model;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @return mixed
     * @throws UnreachableValueException
     */
    public function make()
    {
        if (count($this->in) || count($this->likes)) {
            return $this->getMatchFromPool();
        }

        return $this->createMatchFromConstraints();
    }

    /**
     * @return mixed
     * @throws UnreachableValueException
     */
    protected function getMatchFromPool()
    {
        // Loop through in's and select a random value that's passing constraints
        if (count($this->in)) {
            if (count($matches = array_filter($this->in, [$this, 'valuePassesConstraints']))) {
                return array_random($matches);
            }

            throw new UnreachableValueException("Could not find acceptable value for '{$this->getName()}' - hint: 'whereIn' expression");
        }

        // Try to convert likes to values
        $likes = array_map(function ($expression) {
            return str_replace('_', str_random(1), str_replace('%', '', $expression));
        }, $this->likes);

        if (count($matches = array_filter($likes, [$this, 'valuePassesConstraints']))) {
            return array_random($matches);
        }

        throw new UnreachableValueException("Could not construct acceptable value for '{$this->getName()}' - hint: 'where like' or 'where equal' expression");
    }

    /**
     * @return mixed
     * @throws UnreachableValueException
     */
    protected function createMatchFromConstraints()
    {
        try {
            return (new AttributeReflection($this))->generator()->make();
        } catch (UnreachableValueException $e) {
            if ($this->valuePassesConstraints(null)) {
                return;
            }
            throw $e;
        }
    }

    /**
     * @param $value
     * @return bool
     */
    protected function valuePassesConstraints($value)
    {
        if ($this->gt !== null && $this->gt >= $value) {
            return false;
        }

        if ($this->gte !== null && $this->gte > $value) {
            return false;
        }

        if ($this->lt !== null && $this->lt <= $value) {
            return false;
        }

        if ($this->lte !== null && $this->lte < $value) {
            return false;
        }

        if (count($this->in) && ! in_array($value, $this->in)) {
            return false;
        }

        if (count($this->notIn) && in_array($value, $this->notIn)) {
            return false;
        }

        foreach ($this->likes as $expression) {
            if (! (new LikeInterpreter())->check($value, $expression)) {
                return false;
            }
        }

        return true;
    }

    /**
     * @param ModelAttribute $attribute
     * @return ModelAttribute
     */
    public function merge(self $attribute)
    {
        return $this
            ->gte($attribute->gte)
            ->gt($attribute->gt)
            ->in($attribute->in)
            ->like($attribute->likes)
            ->lt($attribute->lt)
            ->lte($attribute->lte)
            ->notIn($attribute->notIn)
            ->date($attribute->date)
            ->day($attribute->day)
            ->month($attribute->month)
            ->time($attribute->time)
            ->year($attribute->year);
    }
}
