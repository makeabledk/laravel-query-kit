<?php

namespace Makeable\QueryKit\Builder;

class WhereNotBetween extends WhereBetween
{
    /**
     * @param $model
     * @return bool
     */
    public function check($model)
    {
        return ! parent::check($model);
    }
}
