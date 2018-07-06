<?php

namespace Makeable\QueryKit\Tests;

use Makeable\QueryKit\Builder;
use Makeable\QueryKit\QueryKit;

class TestModel extends \Illuminate\Database\Eloquent\Model
{
    use QueryKit;

    /**
     * @var string
     */
    protected $table = 'test_models';

    /**
     * @var array
     */
    protected $guarded = [];

    /**
     * @param Builder $query
     * @param $name
     * @return Builder
     */
    public function scopeNameIs($query, $name)
    {
        return $query->where('name', $name);
    }

    /**
     * @param Builder $query
     * @param $name
     * @return Builder
     */
    public function scopeNameLike($query, $name)
    {
        return $query->where('name', 'like', $name);
    }

    /**
     * @param Builder $query
     * @param $names
     * @return Builder
     */
    public function scopeNameIn($query, $names)
    {
        return $query->whereIn('name', $names);
    }

    /**
     * @param Builder $query
     * @param $operator
     * @param $age
     * @return Builder
     */
    public function scopeAge($query, $operator, $age)
    {
        return $query->where('age', $operator, $age);
    }

    /**
     * @param Builder $query
     * @return Builder
     */
    public function scopeHasAge($query)
    {
        return $query->whereNotNull('age');
    }

    /**
     * @param Builder $query
     * @return Builder
     */
    public function scopeNoAge($query)
    {
        return $query->whereNull('age');
    }

    /**
     * @param Builder $query
     * @return Builder
     */
    public function scopeOld($query)
    {
        return $query->age('>=', 80);
    }

    /**
     * @param Builder $query
     * @return Builder
     */
    public function scopeJaneOrJohn($query)
    {
        return $query->where('name', 'LIKE', 'Jane%')
            ->orWhere('name', 'LIKE', 'John%');
    }

    /**
     * @param Builder $query
     * @return Builder
     */
    public function scopeHasNameOrAge($query)
    {
        return $query->whereNotNull('name')->orWhereNotNull('age');
    }

    /**
     * @param Builder $query
     * @return Builder
     */
    public function scopeMissingNameOrAge($query)
    {
        return $query->whereNull('name')->orWhereNull('age');
    }
}
