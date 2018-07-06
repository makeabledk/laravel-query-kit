<?php

namespace Makeable\QueryKit\Constraints;

interface ConstraintContract
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
