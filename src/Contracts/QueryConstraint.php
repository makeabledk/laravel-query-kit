<?php

namespace Makeable\QueryKit\Contracts;

interface QueryConstraint
{
    /**
     * @param $model
     *
     * @return mixed
     */
    public function check($model);

    /**
     * @param $model
     *
     * @return mixed
     */
    public function make($model);
}
