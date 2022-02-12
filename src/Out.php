<?php
namespace Punch;

use Punch\Runable;
use Facebook\WebDriver\WebDriverBy;
use Facebook\WebDriver\Exception\WebDriverException;
use Punch\Output;

class Out implements Runable
{
    public static function run($driver)
    {
        try {
            // Find the button to click that logs out
            $out = $driver->findElement(
                WebDriverBy::id(CLOCK_PUNCH_OUT_ID)
            );
    
            // Click the element to logout
            $out->click();
            if (TIMESHEET) { // update to be a config const for debug/ output/ time sheet
                Output::print(
                    'Clocked Out',
                    '' // just print the date
                );
            }
        } catch (WebDriverException $th) {
            Output::print(
                'ERROR',
                'No clock out button was not found, you may already be clocked in. Or there may be an error with the css id provided.' 
            );
        }
    }
}