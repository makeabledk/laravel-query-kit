<?php

namespace Makeable\QueryKit\Builder;

use Carbon\Carbon;

class WhereTime extends Where
{
    /**
     * @param $model
     * @return Carbon
     */
    protected function attribute($model)
    {
        $attribute = $model->{$this->property};
        $attribute = $attribute instanceof Carbon ? $attribute : Carbon::parse($model);

        return $attribute->toTimeString();
    }
}
