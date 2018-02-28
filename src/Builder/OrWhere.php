<?php

namespace Makeable\QueryKit\Builder;

use Makeable\QueryKit\Contracts\OrWhereConstraint;
use Makeable\QueryKit\Contracts\QueryConstraint;

class OrWhere implements QueryConstraint, OrWhereConstraint
{
    /**
     * Where constructor.
     *
     * @param array ...$args
     */
    public function __construct(...$args)
    {

    }

    /**
     * @param $model
     *
     * @return bool
     */
    public function check($model)
    {

    }
}
