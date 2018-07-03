<?php

namespace Makeable\QueryKit\Builder;

use Carbon\Carbon;

class WhereYear extends Where
{
    /**
     * @param $model
     * @return int
     */
    protected function attribute($model)
    {
        $attribute = $model->{$this->property};
        $attribute = $attribute instanceof Carbon ? $attribute : Carbon::parse($model);

        return $attribute->year;
    }

    /**
     * @return mixed
     */
    protected function value()
    {
        return (int) $this->value;
    }
}
