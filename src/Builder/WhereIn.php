<?php

namespace Makeable\QueryKit\Builder;

use Makeable\QueryKit\Contracts\QueryConstraint;

class WhereIn implements QueryConstraint
{
    /**
     * @var mixed
     */
    private $property;

    /**
     * @var array
     */
    private $haystack;

    /**
     * WhereNull constructor.
     *
     * @param $property
     * @param $haystack
     */
    public function __construct($property, $haystack)
    {
        if ($haystack instanceof \Illuminate\Support\Collection) {
            $haystack = $haystack->toArray();
        }
        $this->property = $property;
        $this->haystack = $haystack;
    }

    /**
     * @param $model
     *
     * @return bool
     */
    public function check($model)
    {
        return in_array($model->{$this->property}, $this->haystack);
    }
}
