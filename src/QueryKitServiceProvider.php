<?php

namespace Makeable\QueryKit;

use Illuminate\Support\ServiceProvider;
use Makeable\QueryKit\Builder\Builder;
use Makeable\QueryKit\Builder\OrWhere;
use Makeable\QueryKit\Builder\Where;
use Makeable\QueryKit\Builder\WhereIn;
use Makeable\QueryKit\Builder\WhereNotNull;
use Makeable\QueryKit\Builder\WhereNull;

class QueryKitServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register()
    {
        Builder::registerConstraint(OrWhere::class);
        Builder::registerConstraint(Where::class);
        Builder::registerConstraint(WhereIn::class);
        Builder::registerConstraint(WhereNull::class);
        Builder::registerConstraint(WhereNotNull::class);
    }
}
