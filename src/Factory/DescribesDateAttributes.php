<?php

namespace Makeable\QueryKit\Factory;

trait DescribesDateAttributes
{
    /**
     * @return string
     */
    abstract public function getName();

    /**
     * @var string
     */
    public $date;

    /**
     * @var int
     */
    public $day;

    /**
     * @var int
     */
    public $month;

    /**
     * @var string
     */
    public $time;

    /**
     * @var int
     */
    public $year;

    /**
     * @param string $date
     * @return $this
     */
    public function date(string $date)
    {
        return $this->setFirstOrFail('date', $date);
    }

    /**
     * @param int $day
     * @return $this
     */
    public function day(int $day)
    {
        return $this->setFirstOrFail('day', $day);
    }

    /**
     * @param int $month
     * @return $this
     */
    public function month(int $month)
    {
        return $this->setFirstOrFail('month', $month);
    }

    /**
     * @param string $time
     * @return $this
     */
    public function time(string $time)
    {
        return $this->setFirstOrFail('time', $time);
    }

    /**
     * @param int $year
     * @return $this
     */
    public function year(int $year)
    {
        return $this->setFirstOrFail('year', $year);
    }

    /**
     * @param $property
     * @param $value
     * @return $this
     */
    protected function setFirstOrFail($property, $value)
    {
        if ($this->$property !== null && $this->$property != $value) {
            throw new UnreachableValueException("{$this->getName()} {$property} cannot both be {$this->$property} and {$value}");
        }

        $this->$property = $value;

        return $this;
    }
}