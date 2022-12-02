<?php
namespace Punch;

interface Runable
{
    public static function run($runner, $driver);
}