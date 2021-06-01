<?php
namespace Punch;

interface Skippable
{
    public static $label = '';
    public static $date = '';

    public static function set($label,$date);
}