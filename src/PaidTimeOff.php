<?php

namespace Punch;

use Punch\Skipable;

class PaidTimeOff implements Skipable
{
    public static $label = '';
    public static $date = '';

    public static function set($date, $label ='Paid Time Off')
    {
        self::$label = $label;
        self::$date = $date;
        return new static;
    }
}
