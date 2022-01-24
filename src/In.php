<?php
namespace Punch;

use Punch\Runable;
use Facebook\WebDriver\WebDriverBy;
use Facebook\WebDriver\Exception\WebDriverException;
use Punch\Output;

class In implements Runable
{
    public static function run($driver)
    {
        try {
            $in = $driver->findElement(
                WebDriverBy::id(CLOCK_PUNCH_IN_ID)
            );

            // Click the element to login
            $in->click();
            if (TIMESHEET) { // update to be a config const for debug/ output/ time sheet
                Output::print(
                    'Clocked In',
                    date(Output::PUNCH_TIME_SHEET_FORMAT)
                );
            }
        } catch (WebDriverException $th) {
            Output::print(
                'ERROR',
                'No clock in button was not found, you may already be clocked in. Or there may be an error with the css id provided. '
                . date(Output::PUNCH_TIME_SHEET_FORMAT)
            );
        }

    }
}