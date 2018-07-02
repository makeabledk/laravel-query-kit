<?php

namespace Makeable\QueryKit;

use Illuminate\Support\ServiceProvider;
use Makeable\QueryKit\Builder\Builder;
use Makeable\QueryKit\Builder\OrWhere;
use Makeable\QueryKit\Builder\OrWhereDate;
use Makeable\QueryKit\Builder\OrWhereDay;
use Makeable\QueryKit\Builder\OrWhereIn;
use Makeable\QueryKit\Builder\OrWhereMonth;
use Makeable\QueryKit\Builder\OrWhereNotIn;
use Makeable\QueryKit\Builder\OrWhereNotNull;
use Makeable\QueryKit\Builder\OrWhereNull;
use Makeable\QueryKit\Builder\OrWhereTime;
use Makeable\QueryKit\Builder\OrWhereYear;
use Makeable\QueryKit\Builder\Where;
use Makeable\QueryKit\Builder\WhereDate;
use Makeable\QueryKit\Builder\WhereDay;
use Makeable\QueryKit\Builder\WhereIn;
use Makeable\QueryKit\Builder\WhereMonth;
use Makeable\QueryKit\Builder\WhereNotIn;
use Makeable\QueryKit\Builder\WhereNotNull;
use Makeable\QueryKit\Builder\WhereNull;
use Makeable\QueryKit\Builder\WhereTime;
use Makeable\QueryKit\Builder\WhereYear;

class QueryKitServiceProvider extends ServiceProvider
{
    /**
     *
     */
    public function register()
    {
        Builder::registerConstraint(OrWhere::class);
        Builder::registerConstraint(OrWhereDate::class);
        Builder::registerConstraint(OrWhereDay::class);
        Builder::registerConstraint(OrWhereMonth::class);
        Builder::registerConstraint(OrWhereIn::class);
        Builder::registerConstraint(OrWhereNull::class);
        Builder::registerConstraint(OrWhereNotIn::class);
        Builder::registerConstraint(OrWhereNotNull::class);
        Builder::registerConstraint(OrWhereTime::class);
        Builder::registerConstraint(OrWhereYear::class);
        Builder::registerConstraint(Where::class);
        Builder::registerConstraint(WhereDate::class);
        Builder::registerConstraint(WhereDay::class);
        Builder::registerConstraint(WhereMonth::class);
        Builder::registerConstraint(WhereIn::class);
        Builder::registerConstraint(WhereNull::class);
        Builder::registerConstraint(WhereNotIn::class);
        Builder::registerConstraint(WhereNotNull::class);
        Builder::registerConstraint(WhereTime::class);
        Builder::registerConstraint(WhereYear::class);
    }
}
