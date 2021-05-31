<?php
// Composer autoload
require_once dirname(__DIR__) . '/vendor/autoload.php';

// Custom autoloader for php-punch
spl_autoload_register(
    function ($class) {
        $path = explode('\\', $class);
        include_once __DIR__ . DIRECTORY_SEPARATOR . $path[1] . '.php';
    }
);

// Web site url used to clock in or out
define('CLOCK_URL', '');

// User credentials
define('CLOCK_USER', '');
define('CLOCK_PASS', '');

// css ids of the username, password, and log in/out buttons on the web page
define('CLOCK_USER_FIELD_ID', '');
define('CLOCK_PASS_FIELD_ID', '');
define('CLOCK_LOGIN_FIELD_ID', '');
define('CLOCK_LOGOUT_FIELD_ID', '');

// css ids of the elements to find once logged in
define('CLOCK_PUNCH_IN_ID', '');
define('CLOCK_PUNCH_OUT_ID', '');
// css id of an element that is present after logging in
// Currently tied to a drop down click and a 2nd click to the logout button
define('CLOCK_PUNCH_VERIFY_LOGIN_ID', '');

define('MAX_WAIT', 300);

// Generates a list of common holidays that may fall during a work week
// Customize as neccesary
$currentYear = date("Y");
$dateFormat = 'd-m-Y';
define('HOLIDAYS', array(
    'New Years Day'    => date($dateFormat, strtotime("01/01/$currentYear")),
    'MLK Day'          => date($dateFormat, strtotime("third monday of January $currentYear")),
    'Memorial Day'     => date($dateFormat, strtotime("last monday of May $currentYear")),
    'Independence Day' => date($dateFormat, strtotime("07/04/$currentYear")),
    'Labor Day'        => date($dateFormat, strtotime("first monday of September $currentYear")),
    'Veterans Day'     => date($dateFormat, strtotime("11/11/$currentYear")),
    'Columbus Day'     => date($dateFormat, strtotime("second monday of October $currentYear")),
    'Thanksgiving Day' => date($dateFormat, strtotime("fourth thursday of Novemeber $currentYear")),
    'Christmas Day'    => date($dateFormat, strtotime("12/25/$currentYear"))
));

// Remove these from memory
unset($currentYear);
unset($dateFormat);
