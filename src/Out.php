<?php
namespace Punch;

use \Punch\Runable;
use Facebook\WebDriver\WebDriverBy;

class Out implements Runable
{
    public static function run($driver)
    {
        // Find the button to click that logs out
        $out = $driver->findElement(
            WebDriverBy::id(CLOCK_PUNCH_OUT_ID)
        );

        // Click the element to logout
        //$out->click();
    }
}