<?php
namespace Punch;

use Punch\Runable;
use Facebook\WebDriver\WebDriverBy;

class In implements Runable
{
    public static function run($driver)
    {
        $in = $driver->findElement(
            WebDriverBy::id(CLOCK_PUNCH_IN_ID)
        );

        // Click the element to login
        $in->click();
        if (TIMESHEET) { // update to be a config const for debug/ output/ time sheet
            echo '"Clocked In","' . date(self::PUNCH_TIME_SHEET_FORMAT) . '"' . "\n";
        }
    }
}