<?php

namespace Makeable\QueryKit\Constraints;

use Carbon\Carbon;

class WhereDay extends Where
{
    /**
     * @param $model
     * @return Carbon
     */
    protected function attribute($model)
    {
        $attribute = $model->{$this->property};
        $attribute = $attribute instanceof Carbon ? $attribute : Carbon::parse($model);

        return $attribute->day;
    }

    /**
     * @return mixed
     */
    protected function value()
    {
        return (int) $this->value;
    }
}
