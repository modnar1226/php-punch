<?php
namespace Punch;

use Punch\Runable;
use Facebook\WebDriver\WebDriverBy;
use Facebook\WebDriver\WebDriverExpectedCondition;

class Login implements Runable
{
    public static function run($driver)
    {
        // Find username element by its id, write the username inside
        $driver->findElement(WebDriverBy::id(CLOCK_USER_FIELD_ID)) // find username input element
            ->sendKeys(CLOCK_USER); // fill the input box

        // Find password element by its id, write the password inside
        $driver->findElement(WebDriverBy::id(CLOCK_PASS_FIELD_ID)) // find password input element
            ->sendKeys(CLOCK_PASS); // fill the input box

        // Find the Item to click
        $loginButton = $driver->findElement(
            WebDriverBy::id(CLOCK_LOGIN_FIELD_ID)
        );

        // Click the element to navigate to punch screen
        $loginButton->click();

        // verify the login, wait 10 seconds until the user drop down is found.
        $driver->wait(10, 1)->until(
            WebDriverExpectedCondition::presenceOfElementLocated(
                WebDriverBy::id(CLOCK_PUNCH_VERIFY_LOGIN_ID)
            )
        );
    }
}