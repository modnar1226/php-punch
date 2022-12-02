<?php
namespace Punch;

use Punch\Runable;
use Facebook\WebDriver\WebDriverBy;
use Facebook\WebDriver\Exception\WebDriverException;
use Punch\Output;

class In implements Runable
{
    public static function run($runner, $driver)
    {
        if ($runner->processSteps()) {
            if (TIMESHEET) {
                Output::print('Clocked In', '');
            }
        }
        //     $in = $driver->findElement(
        //         WebDriverBy::id(CLOCK_PUNCH_IN_ID)
        //     );

        //     // Click the element to login
        //     $in->click();
        //     if (TIMESHEET) {
        //         Output::print('Clocked In','');
        //     }
    }
}