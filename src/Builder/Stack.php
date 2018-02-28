<?php

namespace Makeable\QueryKit\Builder;

use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use Makeable\QueryKit\Contracts\OrWhereConstraint;
use Makeable\QueryKit\Contracts\QueryConstraint;

class Stack
{
    /**
     * @var Collection
     */
    protected $stack;

    /**
     * Stack constructor.
     */
    public function __construct()
    {
        $this->stack = collect();
    }

    /**
     * @param $method
     * @param $parameters
     * @return mixed
     */
    public function __call($method, $parameters)
    {
        return $this->stack->$method(...$parameters);
    }

    /**
     * @param QueryConstraint $constraint
     * @return Collection
     */
    public function apply(QueryConstraint $constraint)
    {
        if ($this->constraintImplements($constraint, OrWhereConstraint::class)) {
            return $this->forkLast($constraint);
        }
        return $this->push($constraint);
    }

    /**
     * @param $constraint
     * @param $contract
     * @return bool
     */
    protected function constraintImplements($constraint, $contract)
    {
        return array_key_exists($contract, class_implements($constraint));
    }

    /**
     * @param $constraint
     * @return Collection
     */
    protected function push($constraint)
    {
        return $this->stack->push(Arr::wrap($constraint));
    }

    /**
     * @param $constraint
     * @return Collection
     */
    protected function forkLast($constraint)
    {
        return $this->push(
            array_merge($this->stack->pop(), Arr::wrap($constraint))
        );
    }
}