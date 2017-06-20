<?php

namespace Makeable\QueryKit\Builder;

use BadMethodCallException;
use Makeable\QueryKit\Contracts\QueryConstraint;

class Where implements QueryConstraint
{
    /**
     * @var mixed
     */
    private $property;

    /**
     * @var mixed
     */
    private $operator;

    /**
     * @var mixed
     */
    private $value;

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
     * @param $model
     *
     * @return bool
     */
    public function check($model)
    {
        switch ($this->operator) {
            case '=':
                return $model->{$this->property} === $this->value;
            break;
            case '>=':
                return $model->{$this->property} >= $this->value;
            break;
            case '<=':
                return $model->{$this->property} <= $this->value;
            break;
            case '<>':
                return $model->{$this->property} !== $this->value;
            break;
            case '>':
                return $model->{$this->property} > $this->value;
            break;
            case '<':
                return $model->{$this->property} < $this->value;
            break;
            case 'like':
                $pattern = preg_quote($this->value);
                $pattern = str_replace('%', '(.*?)', $pattern);
                $pattern = str_replace('_', '(.)', $pattern);
                $pattern = '/^'.$pattern.'$/s';

                return preg_match($pattern, $model->{$this->property}) != false;
            break;
        }
        throw new BadMethodCallException('Operator not supported: '.$this->operator);
    }

    /**
     * @param $property
     * @param $operator
     * @param null $value
     *
     * @return array
     */
    private function normalize($property, $operator, $value = null)
    {
        if ($value === null) {
            $value = $operator;
            $operator = '=';
        }

        return [$property, $operator, $value];
    }
}
