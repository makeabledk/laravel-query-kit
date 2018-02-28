<?php

namespace Makeable\QueryKit\Builder;

use Makeable\QueryKit\Contracts\OrWhereConstraint;
use Makeable\QueryKit\Contracts\QueryConstraint;

class OrWhere extends Where implements QueryConstraint, OrWhereConstraint
{
}
