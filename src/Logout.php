<?php
namespace Punch;

use Punch\Runable;
use Facebook\WebDriver\WebDriverBy;

class Logout implements Runable
{
    public static function run($driver)
    {
        // Find the Item to click
        $userOptions = $driver->findElement(
            WebDriverBy::id(CLOCK_PUNCH_VERIFY_LOGIN_ID)
        );
        $userOptions->click();

        // Find the Item to click
        $logoutBtn = $driver->findElement(
            WebDriverBy::id(CLOCK_LOGOUT_FIELD_ID)
        );

        // Logout
        $logoutBtn->click();
    }
}