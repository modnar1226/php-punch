<?php
// Composer autoload
require_once dirname(__DIR__) . '/vendor/autoload.php';
// Custom autoloader for php-punch
$classLoader = new \Composer\Autoload\ClassLoader();
$classLoader->addPsr4("Punch\\", __DIR__, true);
$classLoader->register();

// Web site url used to clock in or out
define('CLOCK_URL', 'http://localhost/');

// User credentials
define('CLOCK_USER', 'username');
define('CLOCK_PASS', 'password');

// css ids of the username, password, and log in/out buttons on the web page
define('CLOCK_USER_FIELD_ID', 'username');
define('CLOCK_PASS_FIELD_ID', 'password');
define('CLOCK_LOGIN_FIELD_ID', 'login');
define('CLOCK_LOGOUT_FIELD_ID', 'logout');

// css ids of the elements to find once logged in
define('CLOCK_PUNCH_IN_ID', 'punch_in');
define('CLOCK_PUNCH_OUT_ID', 'punch_out');
// css id of an element that is present after logging in
// Currently tied to a drop down click and a 2nd click to the logout button
define('CLOCK_PUNCH_VERIFY_LOGIN_ID', 'user_dropdown');

// Validation Error css elements
define('CLOCK_PUNCH_ERROR_ID', 'error text id');
define('CLOCK_PUNCH_ERROR_TEXT', 'error text css path');

define('MAX_WAIT', 5);
// echo text in csv format.
define('TIMESHEET',false);
// echo more detail about steps taken
define('DEBUG', false);

//define whether to observe holidays
define('OBSERVE_HOLIDAYS', true);

$currentYear = date("Y");
$dateFormat = 'd-m-Y';
$independenceDayTime = strtotime("07/04/$currentYear");

if (OBSERVE_HOLIDAYS) {
    // function to determine if forth of july is on a sunday.
    $isTheFourthOfJulyOnSunday = function ($time) {
        return ((int) date('N', $time) === 7);
    };
    
    if ($isTheFourthOfJulyOnSunday($independenceDayTime)) {
        $independenceDayTime = strtotime("07/05/$currentYear");
    }
}

// Generates a list of common holidays that may fall during a work week
// Customize as neccesary
define('HOLIDAYS', array(
    //'New Years Day'    => date($dateFormat, strtotime("01/01/$currentYear")),
    //'MLK Day'          => date($dateFormat, strtotime("third monday of January $currentYear")),
    //'Memorial Day'     => date($dateFormat, strtotime("last monday of May $currentYear")),
    //'Independence Day' => date($dateFormat, $independenceDayTime),
    //'Labor Day'        => date($dateFormat, strtotime("first monday of September $currentYear")),
    //'Veterans Day'     => date($dateFormat, strtotime("11/11/$currentYear")),
    //'Columbus Day'     => date($dateFormat, strtotime("second monday of October $currentYear")),
    //'Thanksgiving Day' => date($dateFormat, strtotime("fourth thursday of November $currentYear")),
    //'Christmas Day'    => date($dateFormat, strtotime("12/25/$currentYear"))
));
unset($currentYear);

define('PAID_TIME_OFF_DAYS', array(
    //date($dateFormat, strtotime("01/01/2020"))
));

// Remove these from memory
unset($dateFormat);
unset($independenceDayTime);
