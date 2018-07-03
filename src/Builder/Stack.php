<?php

namespace Makeable\QueryKit\Builder;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Makeable\QueryKit\Contracts\QueryConstraint;
use Makeable\QueryKit\Factory\ModelBuilder;

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

    /**
     * @param Stack $stack
     * @param $model
     * @return bool
     */
    public function check($model)
    {
        return $this->tracks->first(function ($track) use ($model) {
            return static::passesTrack($track, $model);
        }) !== null;
    }

    /**
     * @param Collection $track
     * @param $model
     * @return bool
     */
    protected function passesTrack($track, $model)
    {
        return $track->first(function (QueryConstraint $constraint) use ($model) {
            return ! $constraint->check($model);
        }) === null;
    }

    /**
     * @param Model $model
     * @return array
     */
    public function makeAttributes($model)
    {
        if (! $constraints = $this->tracks->random()) {
            return [];
        }

        return $constraints
            ->reduce(function (ModelBuilder $builder, QueryConstraint $constraint) use ($model) {
                $builder->mergeAttributes($constraint->make($model));
            }, new ModelBuilder($model))
            ->makeAttributes();
    }
}
