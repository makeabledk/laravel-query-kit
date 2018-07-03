<?php

namespace Makeable\QueryKit\Factory;

class ModelAttribute
{
    use DescribesAttributes;

    /**
     * @var string
     */
    protected $name;

    /**
     * @param $name
     */
    public function __construct($name)
    {
        $this->name = $name;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    public function make()
    {
        if (count($in = $this->in) && count($this->notIn)) {
            if (count($in = array_intersect($in, $this->notIn)) === 0) {
                throw new UnreachableValueException("{$this->getName()}");
            }
        }


    }

    /**
     * @param ModelAttribute $attribute
     * @return ModelAttribute
     */
    public function merge(ModelAttribute $attribute)
    {
        return $this
            ->gte($attribute->gte)
            ->gt($attribute->gt)
            ->in($attribute->in)
            ->like($attribute->likes)
            ->lt($attribute->lt)
            ->lte($attribute->lte)
            ->notIn($attribute->notIn)
            ->date($attribute->date)
            ->day($attribute->day)
            ->month($attribute->month)
            ->time($attribute->time)
            ->year($attribute->year);
    }
}