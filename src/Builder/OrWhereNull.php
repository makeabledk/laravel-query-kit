<?php

namespace Makeable\QueryKit\Builder;

use Makeable\QueryKit\Contracts\OrConstraint;
use Makeable\QueryKit\Contracts\QueryConstraint;

class OrWhereNull extends WhereNull implements QueryConstraint, OrConstraint
{

}
