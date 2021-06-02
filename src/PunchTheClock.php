<?php
namespace Punch;

use Punch\Punchable;
use Punch\In;
use Punch\Out;
use Punch\Login;
use Punch\Logout;
use Facebook\WebDriver\Remote\RemoteWebDriver;
use Facebook\WebDriver\Remote\DesiredCapabilities;
use Facebook\WebDriver\Chrome\ChromeOptions;
use Facebook\WebDriver\Exception\NoSuchElementException;

class PunchTheClock extends Punchable
{
    private $serverUrl = 'http://localhost:9515';
    private $driver = null;

    public function __construct()
    {
        $this->validate($_SERVER['argv']);

        if (DEBUG) {
            echo '"DEBUG","Checking for holidays..."' . "\n";
        }
        // Check for holidays first, no need to punch anything if we're off today!
        foreach (HOLIDAYS as $label => $date) {
            // Exit with a message about the holiday if returns true
            ($this->isTodayAHoliday(Holiday::set($label, $date)) === true) ? exit('"Holiday","Today '.date('m/d/Y').' is '.$label.'"'."\n") : null ;
        }

        if (DEBUG) {
            echo '"DEBUG","Its not a holiday, we need to punch the clock"' . "\n";
        }

        // Create an instance of ChromeOptions:
        $chromeOptions = new ChromeOptions();

        // Configure $chromeOptions
        
        // Set to run without a window
        $chromeOptions->addArguments(['--headless']);

        // Create $capabilities and add configuration from ChromeOptions
        $capabilities = DesiredCapabilities::chrome();
        $capabilities->setCapability(ChromeOptions::CAPABILITY_W3C, $chromeOptions);

        // Start Chrome
        $this->driver = RemoteWebDriver::create($this->serverUrl, $capabilities);

        
        // Gets the punch direction user input, in or out
        $direction = $_SERVER['argv'][1];

        // Wait to simulate human error, between 0 seconds and either the users input or a max of MAX_WAIT in seconds
        $timeToWait = (!empty($_SERVER['argv'][2]) && is_numeric($_SERVER['argv'][2])) ? $_SERVER['argv'][2] : MAX_WAIT;
        if (DEBUG) {
            echo '"DEBUG","Time to wait: '. $timeToWait . '"' . "\n";
        }
        sleep(rand(0, $timeToWait));

        $this->punch($direction);
    }

    protected function punch($direction)
    {
        if (DEBUG) {
            echo '"DEBUG","Loading url: ' . CLOCK_URL . '"' . "\n";
        }
        $this->driver->get(CLOCK_URL);

        if (DEBUG) {
            echo '"DEBUG","Logging in."' . "\n";
        }
        // Check user input & login
        // common login for both scenarios
        Login::run($this->driver);

        // Check user input & login
        if (DEBUG) {
            echo '"DEBUG","Clocking In or Out?: ' . $direction . '"' . "\n";
        }

        // punch in 
        in_array($direction, self::IN) ? In::run($this->driver) : null;
        // or punch out
        in_array($direction, self::OUT) ? Out::run($this->driver) : null;

        if (DEBUG) {
            echo '"DEBUG","Logging out."' . "\n";
        }
        // common logout for both scenarios
        Logout::run($this->driver);
    }

    public function __destruct()
    {
        if ($this->driver !== null) {
            if (DEBUG) {
                echo '"DEBUG","Closing the browser."' . "\n";
            }
            // close the browser
            $this->driver->quit();
        }
    }
}