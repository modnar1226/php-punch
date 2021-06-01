<?php
namespace Punch;

use Punch\Skipable;

class Holiday implements Skipable
{
    public static $label = '';
    public static $date = '';

    public static function set($label, $date)
    {
        self::$label = $label;
        self::$date = $date;
        return new static;
    }
}