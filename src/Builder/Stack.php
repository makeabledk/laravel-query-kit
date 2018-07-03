<?php

namespace Makeable\QueryKit\Builder;

use Illuminate\Support\Collection;
use Makeable\QueryKit\Contracts\OrConstraint;
use Makeable\QueryKit\Contracts\QueryConstraint;

class Stack
{
    /**
     * @var Collection
     */
    protected $tracks;

    /**
     * Stack constructor.
     */
    public function __construct()
    {
        $this->tracks = collect();
    }

    /**
     * @param Stack $stack
     * @param $model
     * @return bool
     */
    public static function check(self $stack, $model)
    {
        return $stack->tracks->first(function ($track) use ($model) {
            return static::passesTrack($track, $model);
        }) !== null;
    }

    /**
     * @param Collection $track
     * @param $model
     * @return bool
     */
    protected static function passesTrack($track, $model)
    {
        return $track->first(function (QueryConstraint $constraint) use ($model) {
            return ! $constraint->check($model);
        }) === null;
    }

    /**
     * @param QueryConstraint $constraint
     * @param bool $or
     * @return Stack
     */
    public function apply(QueryConstraint $constraint, $or = false)
    {
        if ($or) {
            return $this->newTrack($constraint);
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
     * @return Stack
     */
    protected function newTrack($constraint)
    {
        $this->tracks->push(collect([$constraint]));

        return $this;
    }

    /**
     * @param $constraint
     * @return Stack
     */
    protected function push($constraint)
    {
        if (($current = $this->tracks->last()) === null) {
            return $this->newTrack($constraint);
        }

        $current->push($constraint);

        return $this;
    }
}
