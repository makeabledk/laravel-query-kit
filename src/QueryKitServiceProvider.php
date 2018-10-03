<?php

namespace Makeable\QueryKit;

use Illuminate\Support\ServiceProvider;
use Makeable\QueryKit\Constraints\Where;
use Makeable\QueryKit\Constraints\WhereBetween;
use Makeable\QueryKit\Constraints\WhereDate;
use Makeable\QueryKit\Constraints\WhereDay;
use Makeable\QueryKit\Constraints\WhereIn;
use Makeable\QueryKit\Constraints\WhereMonth;
use Makeable\QueryKit\Constraints\WhereNotBetween;
use Makeable\QueryKit\Constraints\WhereNotIn;
use Makeable\QueryKit\Constraints\WhereNotNull;
use Makeable\QueryKit\Constraints\WhereNull;
use Makeable\QueryKit\Constraints\WhereTime;
use Makeable\QueryKit\Constraints\WhereYear;

class QueryKitServiceProvider extends ServiceProvider
{
    public function register()
    {
        Builder::registerConstraint(Where::class);
        Builder::registerConstraint(WhereBetween::class);
        Builder::registerConstraint(WhereDate::class);
        Builder::registerConstraint(WhereDay::class);
        Builder::registerConstraint(WhereMonth::class);
        Builder::registerConstraint(WhereIn::class);
        Builder::registerConstraint(WhereNull::class);
        Builder::registerConstraint(WhereNotBetween::class);
        Builder::registerConstraint(WhereNotIn::class);
        Builder::registerConstraint(WhereNotNull::class);
        Builder::registerConstraint(WhereTime::class);
        Builder::registerConstraint(WhereYear::class);
    }
}
