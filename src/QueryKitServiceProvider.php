<?php

namespace Makeable\QueryKit;

use Illuminate\Support\ServiceProvider;
use Makeable\QueryKit\Builder\Builder;
use Makeable\QueryKit\Builder\Where;
use Makeable\QueryKit\Builder\WhereBetween;
use Makeable\QueryKit\Builder\WhereDate;
use Makeable\QueryKit\Builder\WhereDay;
use Makeable\QueryKit\Builder\WhereIn;
use Makeable\QueryKit\Builder\WhereMonth;
use Makeable\QueryKit\Builder\WhereNotBetween;
use Makeable\QueryKit\Builder\WhereNotIn;
use Makeable\QueryKit\Builder\WhereNotNull;
use Makeable\QueryKit\Builder\WhereNull;
use Makeable\QueryKit\Builder\WhereTime;
use Makeable\QueryKit\Builder\WhereYear;

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
