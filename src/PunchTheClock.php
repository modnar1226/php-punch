<?php
namespace Punch;

use \Punch\Punchable;
use \Punch\In;
use \Punch\Out;
use Facebook\WebDriver\Remote\RemoteWebDriver;
use Facebook\WebDriver\Remote\DesiredCapabilities;
use Facebook\WebDriver\Chrome\ChromeOptions;
use Symfony\Component\Process\Process;

class PunchTheClock extends Punchable
{
    const IN = array(
        'in', 'In', 'IN', 'en', 'En', 'EN'
    );

    const OUT = array(
        'out', 'Out', 'OUT', 'fuera', 'Fuera', 'FUERA'
    );

    private $serverUrl = 'http://localhost:9515';
    private $driver = null;
    private $process = null;

    public function __construct()
    {
        if (empty($_SERVER['argv'][1])) {
            throw new \Exception("You must provide a direction in which to punch! (In or Out?)", 1);
        }

        if (DEBUG) {
            echo '"DEBUG","Checking for holidays..."' . "\n";
        }
        // Check for holidays first, no need to punch anything if we're off today!
        foreach (HOLIDAYS as $label => $date) {
            // Exit with a message about the holiday if returns true
            ($this->isTodayAHoliday(Holiday::set($label, $date)) === true) ? exit('"Holiday","Today '.date('m/d/Y').' is '.$label.'"'."\n") : null ;
        }

        if (DEBUG) {
            echo '"DEBUG","Its not a holiday, we need to clock in."' . "\n";
        }

        $this->process = new Process(['chromedriver']);
        $this->process->start();

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
        $this->login($this->driver);

        // Check user input & login
        if (DEBUG) {
            echo '"DEBUG","Clocking In or Out?: ' . $direction . '"' . "\n";
        }

        if (in_array($direction, self::IN)) {
            IN::run($this->driver);
        }

        // Check user input & logout
        if (in_array($direction, self::OUT)) {
            OUT::run($this->driver);
        }

        if (DEBUG) {
            echo '"DEBUG","Logging out."' . "\n";
        }
        // common logout for both scenarios
        $this->logout($this->driver);
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
        
        if ($this->process !== null) {
            $this->process->stop();
        }
    }
}