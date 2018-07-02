<?php

namespace Makeable\QueryKit\Builder;

use Makeable\QueryKit\Contracts\OrConstraint;
use Makeable\QueryKit\Contracts\QueryConstraint;

class OrWhereBetween extends WhereBetween implements QueryConstraint, OrConstraint
{
}
