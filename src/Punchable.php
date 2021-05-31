<?php
namespace Punch;

use Punch\Holiday;

use Facebook\WebDriver\WebDriverBy;
use Facebook\WebDriver\WebDriverExpectedCondition;

abstract class Punchable
{
    abstract protected function punch($direction);

    protected function login($driver)
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

    protected function logout($driver)
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

    protected function isTodayAHoliday(Holiday $holiday)
    {
        if ($holiday instanceof Skippable) {
            return date('d-m-Y') === $holiday::$date;
        } else {
            throw new \Exception("The object Holiday must interface Skippable", 1);
        }
    }
}