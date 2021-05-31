<?php
namespace Punch;

use \Punch\Punchable;
use \Punch\In;
use \Punch\Out;
use Facebook\WebDriver\Remote\RemoteWebDriver;
use Facebook\WebDriver\Remote\DesiredCapabilities;
use Facebook\WebDriver\Chrome\ChromeOptions;

class PunchTheClock extends Punchable
{
    const IN = array(
        'in', 'In', 'IN', 'en', 'En', 'EN'
    );

    const OUT = array(
        'out', 'Out', 'OUT', 'fuera', 'Fuera', 'FUERA'
    );

    private $serverUrl = 'http://localhost:4444';
    private $driver = null;

    public function __construct()
    {
        // Check for holidays first, no need to punch anything if we're off today!
        foreach (HOLIDAYS as $label => $date) {
            // Exit if returns true
            ($this->isTodayAHoliday(Holiday::set($label, $date)) === true) ? exit() : null ;
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

        if (!empty($_SERVER['argv'][1])) {
            // Gets the punch direction user input, in or out
            $direction = $_SERVER['argv'][1];

            // Wait to simulate human error, between 0 seconds and either the users input or a max of MAX_WAIT in seconds
            $timeToWait = (!empty($_SERVER['argv'][2]) && is_numeric($_SERVER['argv'][2])) ? $_SERVER['argv'][2] : MAX_WAIT;
            sleep(rand(0, $timeToWait));

            $this->punch($direction);
        } else {
            throw new \Exception("You must provide a direction in which to punch! (In or Out?)", 1);
        }
    }

    protected function punch($direction)
    {
        $this->driver->get(CLOCK_URL);

        // common login for both scenarios
        $this->login($this->driver);

        // Check user input & login
        if (in_array($direction, self::IN)) {
            IN::run($this->driver);
        }

        // Check user input & logout
        if (in_array($direction, self::OUT)) {
            OUT::run($this->driver);
        }

        // common logout for both scenarios
        $this->logout($this->driver);
    }

    public function __destruct()
    {
        if ($this->driver !== null) {
            // close the browser
            $this->driver->quit();
        }
    }
}