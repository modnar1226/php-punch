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
        // Create an instance of ChromeOptions:
        $chromeOptions = new ChromeOptions();

        // Configure $chromeOptions, see examples bellow:
        //$chromeOptions->addArguments(['--headless']);

        // Create $capabilities and add configuration from ChromeOptions
        $capabilities = DesiredCapabilities::chrome();
        $capabilities->setCapability(ChromeOptions::CAPABILITY_W3C, $chromeOptions);

        // Start Chrome
        $this->driver = RemoteWebDriver::create($this->serverUrl, $capabilities);

        if (!empty($_SERVER['argv'][1])) {
            $direction = $_SERVER['argv'][1];
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