<?php

namespace Makeable\QueryKit\Builder;

use BadMethodCallException;
use Makeable\QueryKit\Contracts\QueryConstraint;

class Where implements QueryConstraint
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
        [$this->property, $this->operator, $this->value] = $this->normalize(...$args);
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
            break;
            case '>=':
                return $this->attribute($model) >= $this->value();
            break;
            case '<=':
                return $this->attribute($model) <= $this->value();
            break;
            case '<>':
                return $this->attribute($model) !== $this->value();
            break;
            case '>':
                return $this->attribute($model) > $this->value();
            break;
            case '<':
                return $this->attribute($model) < $this->value();
            break;
            case 'like':
                $pattern = preg_quote($this->value());
                $pattern = str_replace('%', '(.*?)', $pattern);
                $pattern = str_replace('_', '(.)', $pattern);
                $pattern = '/^'.$pattern.'$/s';

                return preg_match($pattern, $this->attribute($model)) != false;
            break;
        }
        throw new BadMethodCallException('Operator not supported: '.$this->operator);
    }

    /**
     * @return bool
     */
    protected function isSubQuery()
    {
        return $this->operator === null && is_callable($this->property);
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
}
