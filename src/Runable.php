<?php
namespace Punch;

interface Runable
{
    const PUNCH_TIME_SHEET_FORMAT = 'm/d/Y h:i a';
    public static function run($driver);
}