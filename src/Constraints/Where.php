<?php

namespace Makeable\QueryKit\Constraints;

use BadMethodCallException;
use Makeable\QueryKit\Builder;
use Makeable\QueryKit\Factory\ModelAttribute;

class Where implements ConstraintContract
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
    protected $value;

    /**
     * Where constructor.
     *
     * @param array ...$args
     */
    public function __construct(...$args)
    {
        list($this->property, $this->operator, $this->value) = $this->normalize(...$args);
    }

    /**
     * @param $property
     * @param $operator
     * @param null $value
     *
     * @return array
     */
    protected function normalize($property, $operator = null, $value = null)
    {
        if ($operator !== null && $value === null) {
            $value = $operator;
            $operator = '=';
        }

        $operator = $operator === null ? null : strtolower($operator);

        return [$property, $operator, $value];
    }

    /**
     * @param $model
     *
     * @return bool
     */
    public function check($model)
    {
        if ($this->isSubQuery()) {
            return Builder::make($model, $this->property)->check();
        }

        switch ($this->operator) {
            case '=':
                return $this->attribute($model) === $this->value();

            case '>=':
                return $this->attribute($model) >= $this->value();

            case '<=':
                return $this->attribute($model) <= $this->value();

            case '<>':
                return $this->attribute($model) !== $this->value();

            case '>':
                return $this->attribute($model) > $this->value();

            case '<':
                return $this->attribute($model) < $this->value();

            case 'like':
                return (new LikeInterpreter())->check($this->attribute($model), $this->value());
        }

        $this->invalidOperator();
    }

    /**
     * @param $model
     *
     * @return ModelAttribute|array
     */
    public function make($model)
    {
        if ($this->isSubQuery()) {
            return Builder::make($model, $this->property)->makeAttributes();
        }

        $attribute = new ModelAttribute($this->property);

        switch ($this->operator) {
            case '=':
                return $attribute->like($this->value());

            case '>=':
                return $attribute->gte($this->value());

            case '<=':
                return $attribute->lte($this->value());

            case '<>':
                return $attribute->notIn($this->value());

            case '>':
                return $attribute->gt($this->value());

            case '<':
                return $attribute->lte($this->value());

            case 'like':
                return $attribute->like($this->value());
        }

        $this->invalidOperator();
    }

    /**
     * @return bool
     */
    protected function isSubQuery()
    {
        return $this->operator === null && is_callable($this->property);
    }

    /**
     * @param $model
     * @return mixed
     */
    protected function attribute($model)
    {
        return $model->{$this->property};
    }

    /**
     * @return mixed
     */
    protected function value()
    {
        return $this->value;
    }

    /**
     * @throws BadMethodCallException
     */
    protected function invalidOperator()
    {
        throw new BadMethodCallException('Operator not supported: '.$this->operator);
    }
}
