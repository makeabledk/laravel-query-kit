<?php

namespace Makeable\QueryKit;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Makeable\QueryKit\Constraints\ConstraintContract;
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
     * @param ConstraintContract $constraint
     * @param bool $or
     * @return Stack
     */
    public function apply(ConstraintContract $constraint, $or = false)
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
        return $track->first(function (ConstraintContract $constraint) use ($model) {
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
            ->reduce(function (ModelBuilder $builder, ConstraintContract $constraint) use ($model) {
                $builder->mergeAttributes($constraint->make($model));
            }, new ModelBuilder($model))
            ->makeAttributes();
    }
}
