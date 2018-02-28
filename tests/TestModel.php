<?php

namespace Makeable\QueryKit\Tests;

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
     * @var bool
     */
    public $timestamps = false;

    /**
     * @param $query
     * @param $name
     * @return mixed
     */
    public function scopeNameIs($query, $name)
    {
        return $query->where('name', $name);
    }

    /**
     * @param $query
     * @param $name
     * @return mixed
     */
    public function scopeNameLike($query, $name)
    {
        return $query->where('name', 'like', $name);
    }

    /**
     * @param $query
     * @param $names
     * @return mixed
     */
    public function scopeNameIn($query, $names)
    {
        return $query->whereIn('name', $names);
    }

    /**
     * @param $query
     * @param $operator
     * @param $age
     * @return mixed
     */
    public function scopeAge($query, $operator, $age)
    {
        return $query->where('age', $operator, $age);
    }

    /**
     * @param $query
     * @return mixed
     */
    public function scopeHasAge($query)
    {
        return $query->whereNotNull('age');
    }

    /**
     * @param $query
     * @return mixed
     */
    public function scopeNoAge($query)
    {
        return $query->whereNull('age');
    }

    /**
     * @param $query
     * @return mixed
     */
    public function scopeOld($query)
    {
        return $query->age('>=', 80);
    }
}
