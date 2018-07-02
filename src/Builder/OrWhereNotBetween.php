<?php

namespace Makeable\QueryKit\Builder;

use Makeable\QueryKit\Contracts\OrConstraint;
use Makeable\QueryKit\Contracts\QueryConstraint;

class OrWhereNotBetween extends WhereNotBetween implements QueryConstraint, OrConstraint
{
}
