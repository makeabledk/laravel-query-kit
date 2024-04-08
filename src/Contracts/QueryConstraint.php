<?php

namespace Makeable\QueryKit\Contracts;

interface QueryConstraint
{
    /**
     * @param  $model
     * @return mixed
     */
    public function check($model);
}
