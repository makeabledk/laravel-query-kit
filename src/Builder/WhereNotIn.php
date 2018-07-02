<?php

namespace Makeable\QueryKit\Builder;

class WhereNotIn extends WhereIn
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
