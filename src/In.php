<?php
namespace Punch;

use \Punch\Runable;
use Facebook\WebDriver\WebDriverBy;

class In Extends Login implements Runable
{
    public static function run($driver)
    {
        $in = $driver->findElement(
            WebDriverBy::id(CLOCK_PUNCH_IN_ID)
        );

        // Click the element to login
        //$in->click();
    }
}