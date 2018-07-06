<?php

namespace Makeable\QueryKit\Constraints;

use Carbon\Carbon;

class WhereTime extends Where
{
    /**
     * @param $model
     * @return int
     */
    protected function attribute($model)
    {
        $attribute = $model->{$this->property};
        $attribute = $attribute instanceof Carbon ? $attribute : Carbon::parse($model);

        return $attribute->toTimeString();
    }
}
