<?php
namespace Punch;

use Punch\Runable;
use Facebook\WebDriver\WebDriverBy;
use Facebook\WebDriver\Exception\WebDriverException;
use Punch\Output;

class Out implements Runable
{
    public static function run($runner,$driver)
    {
        if ($runner->processSteps()) {
            if (TIMESHEET) {
                Output::print('Clocked Out', '');
            }
        }
    }
}