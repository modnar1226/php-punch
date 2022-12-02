<?php
namespace Punch;

use Punch\Runable;
use Punch\Output;
use Facebook\WebDriver\WebDriverBy;
use Facebook\WebDriver\WebDriverExpectedCondition;

class Login implements Runable
{
    public static function run($runner, $driver)
    {
        return $runner->processSteps();
        // if (DEBUG) {
        //     Output::print('DEBUG', 'Enter Username');
        // }
        // // Find username element by its id, write the username inside
        // $driver->findElement(WebDriverBy::id(CLOCK_USER_FIELD_ID)) // find username input element
        //     ->sendKeys(CLOCK_USER); // fill the input box

        // if (DEBUG) {
        //     Output::print('DEBUG', 'Enter Password');
        // }
        // // Find password element by its id, write the password inside
        // $driver->findElement(WebDriverBy::id(CLOCK_PASS_FIELD_ID)) // find password input element
        //     ->sendKeys(CLOCK_PASS); // fill the input box

        // if (DEBUG) {
        //     Output::print('DEBUG', 'Find Login Button');
        // }
        // // Find the Item to click
        // $loginButton = $driver->findElement(
        //     WebDriverBy::id(CLOCK_LOGIN_FIELD_ID)
        // );

        // if (DEBUG) {
        //     Output::print('DEBUG','Click Login Button');
        // }
        // // Click the element to navigate to punch screen
        // $loginButton->click();

        // $errorTextLi = [];

        // try {
        //     if (DEBUG) {
        //         Output::print('DEBUG', 'Checking for Errors');
        //     }
        //     // wait until the text element is found
        //     $driver->wait(5, 1)->until(
        //         WebDriverExpectedCondition::presenceOfElementLocated(
        //             WebDriverBy::cssSelector(CLOCK_PUNCH_ERROR_TEXT)
        //         )
        //     );
        //     // Check for log in errors
        //     $errorTextLi = $driver->findElements(
        //         WebDriverBy::cssSelector(CLOCK_PUNCH_ERROR_TEXT)
        //     );
        // } catch (\Throwable $th) {
        //     if (DEBUG) {
        //         Output::print('DEBUG', 'No Errors found');
        //     }
        // }

        
        // if (count($errorTextLi)) {
        //     if (DEBUG) {
        //         Output::print('DEBUG','Login error found');
        //     }

        //     $errorText = "When logging in to the time clock application there was an error,\n";
        //     foreach ($errorTextLi as $element) {
        //         $errorText .= $element->getText();
        //     }

        //     Output::print('ERROR',$errorText);

        //     //$notification_script = "notify-send -u critical -t 0 'Login Authorization Error:' '{$errorText}'";
        //     //$handle = popen($notification_script, 'r');
        //     //pclose($handle);

        //     return false;
        // } else {
        //     if (DEBUG) {
        //         Output::print('DEBUG','Login succeeded');
        //     }
        //     // verify the login, wait 10 seconds until the user drop down is found.
        //     $driver->wait(10, 1)->until(
        //         WebDriverExpectedCondition::presenceOfElementLocated(
        //             WebDriverBy::id(CLOCK_PUNCH_VERIFY_LOGIN_ID)
        //         )
        //     );
            
        //     return true;
        // }
    }
}