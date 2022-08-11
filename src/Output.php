<?php
namespace Punch;

class Output
{
    const PUNCH_TIME_SHEET_FORMAT = 'm/d/Y h:i a';
    public static function print($status, $msg){
        echo '"' . $status . '","' . date(self::PUNCH_TIME_SHEET_FORMAT) . '","'. $msg .'"'. "\n"; 
    }
}