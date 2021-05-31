<?php
namespace Punch;

use \Punch\Skippable;

class Holiday implements Skippable
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