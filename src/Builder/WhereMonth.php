<?php

namespace Makeable\QueryKit\Builder;

use Carbon\Carbon;

class WhereMonth extends Where
{
    /**
     * @param $model
     * @return Carbon
     */
    protected function attribute($model)
    {
        $attribute = $model->{$this->property};
        $attribute = $attribute instanceof Carbon ? $attribute : Carbon::parse($model);

        return $attribute->month;
    }

    /**
     * @return mixed
     */
    protected function value()
    {
        return (int) $this->value;
    }
}
