<?php
namespace Punch;

use Punch\Holiday;

abstract class Punchable
{
    abstract protected function punch($direction);

    protected function isTodayAHoliday(Holiday $holiday)
    {
        if ($holiday instanceof Skipable) {
            return date('d-m-Y') === $holiday::$date;
        } else {
            throw new \Exception("The object Holiday must interface " . Skipable::class, 1);
        }
    }
}