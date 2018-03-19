<?php

namespace Makeable\QueryKit\Builder;

use Makeable\QueryKit\Contracts\OrConstraint;
use Makeable\QueryKit\Contracts\QueryConstraint;

class OrWhereNotNull extends WhereNotNull implements QueryConstraint, OrConstraint
{
}
