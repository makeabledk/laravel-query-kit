<?php

namespace Makeable\QueryKit\Constraints;

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
