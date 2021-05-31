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
