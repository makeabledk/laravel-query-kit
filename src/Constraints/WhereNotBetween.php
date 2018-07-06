<?php

namespace Makeable\QueryKit\Constraints;

class WhereNotBetween extends WhereBetween
{
    /**
     * @param $model
     *
     * @return bool
     */
    public function check($model)
    {
        return ! parent::check($model);
    }
}
