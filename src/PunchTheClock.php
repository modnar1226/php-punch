<?php
namespace Punch;

require_once __DIR__ . '/config.php';

use Facebook\WebDriver\Chrome\ChromeDriver;
use Punch\Punchable;
use Punch\In;
use Punch\Out;
use Punch\Login;
use Punch\Logout;
use Punch\Holiday;
use Punch\PaidTimeOff;
use Punch\Output;
use Punch\Runner;

use Facebook\WebDriver\Remote\RemoteWebDriver;
use Facebook\WebDriver\Remote\DesiredCapabilities;
use Facebook\WebDriver\Chrome\ChromeOptions;
use Facebook\WebDriver\Chrome\ChromeDriverService;
use Facebook\WebDriver\Exception\NoSuchElementException;

class PunchTheClock extends Punchable
{
    private $serverUrl = 'http://localhost:4444';
    private $driver = null;
    private $port = null;

    public function __construct()
    {
        if (PLATFORM !== 'linux' && (DRIVER_EXE_PATH === '' || CHROME_EXE_PATH === '')) {
            throw new \Exception("When the platform is not linux, you must specify the paths to both the Chrome browser and Chromedirver executable files in the config.php file.", 1);
        }

        $this->validate($_SERVER['argv']);

        $this->buildConstants();
        $this->port = CONFIG['port'];
        if (CONFIG['debug']) {
            Output::print('DEBUG', 'Checking for holidays...');
        }
        // Check for holidays first, no need to punch anything if we're off today!
        foreach (HOLIDAYS as $label => $date) {
            // Exit with a message about the holiday if returns true
            ($this->isTodayAHoliday(Holiday::set($date, $label)) === true)
            ? exit(
                '"Holiday","Today '.date('m/d/Y').' is '.$label.'"'."\n"
            )
            : null ;
        }

        // Check for planned days off
        foreach (PAID_TIME_OFF_DAYS as $date) {
            // Exit with a message about the PTO day off if returns true
            ($this->isUsingPaidTImeOff(PaidTimeOff::set($date)) === true)
            ? exit(
                '"' . PaidTimeOff::$label . '","Today ' . date('m/d/Y') . ' we are using ' . PaidTimeOff::$label . '"' . "\n"
            )
            : null;
        }

        if (CONFIG['debug']) {
            Output::print('DEBUG', 'Its not a holiday nor are we using Paid Time Off, we need to punch the clock');
        }

        // Create an instance of ChromeOptions:
        $chromeOptions = new ChromeOptions();

        // Configure $chromeOptions

        // Set to run without a window
        $chromeOptions->addArguments(["--headless"]);
        // force a screen size to make sure mobile layout designs don't interfere
        $chromeOptions->addArguments(['--window-size=1920,1000']);
        $chromeOptions->setBinary(
            CONFIG['platform'] !== 'linux' ? CONFIG['chromeBrowserExecutablePath'] : "/usr/bin/google-chrome"
        );

        // Create $capabilities and add configuration from ChromeOptions
        $capabilities = DesiredCapabilities::chrome();
        $capabilities->setCapability(ChromeOptions::CAPABILITY_W3C, $chromeOptions);
        $capabilities->setCapability('platform', CONFIG['platform']);


        // Start Chrome
        $pathToExecutable = CONFIG['platform'] !== 'linux' ? CONFIG['chromeDriverExecutablePath'] : 'chromedriver';
        $chromeDriverservice = new ChromeDriverService($pathToExecutable, $this->port, ['--port=' . $this->port]);
        $this->driver = ChromeDriver::startUsingDriverService($chromeDriverservice, $capabilities);
        
        // Gets the punch direction user input, in or out
        $direction = $_SERVER['argv'][1];

        // Wait to simulate human error, between 0 min and either the users input or a max of MAX_WAIT in min
        $timeToWait = (((isset($_SERVER['argv'][2])) && $_SERVER['argv'][2] !== null && is_numeric($_SERVER['argv'][2])) ? $_SERVER['argv'][2] : CONFIG['maxWaitTime']);
        if (CONFIG['debug']) {
            Output::print('DEBUG', 'Is input not empty: ' . !empty($_SERVER['argv'][2]));
            Output::print('DEBUG', 'Time to wait input: ' . $_SERVER['argv'][2]);
            Output::print('DEBUG', 'Is input not empty: ' . $timeToWait);
        }

        // multiply the time to wait by 60 to get number of seconds.
        // randomly picking the number of seconds allows for too much variation on the minute of punch.
        // anything between 0-60 seconds is still in the same minute, and kind of pointless
        sleep(mt_rand(0, ($timeToWait * 60)));

        try {
            $this->punch($direction);
        } catch (\Throwable $th) {
            Output::print('ERROR', $th->getMessage());
            // force the browser to close if we have an error, then throw the error
            $this->driverQuit();
            throw $th;
        }
    }

    protected function punch($direction)
    {
        if (CONFIG['debug']) {
            Output::print('DEBUG', 'Loading url: ' . CONFIG['clockUrl']);
        }
        $this->driver->get(CONFIG['clockUrl']);

        if (CONFIG['debug']) {
            Output::print('DEBUG', 'Logging in.');
        }
        // Check user input & login
        // common login for both scenarios
        if (!Login::run(new Runner(CONFIG['steps']['login'], $this->driver),$this->driver)) {
            exit();
        }
        

        // Check user input & login
        if (CONFIG['debug']) {
            Output::print('DEBUG', 'Clocking In or Out?: ' . $direction);
        }

        // punch in 
        in_array($direction, self::IN) ? In::run(new Runner(CONFIG['steps']['clockIn'],$this->driver),$this->driver) : null;
        // or punch out
        in_array($direction, self::OUT) ? Out::run(new Runner(CONFIG['steps']['clockOut'],$this->driver),$this->driver) : null;

        if (CONFIG['debug']) {
            Output::print('DEBUG', 'Logging out.');
        }
        // common logout for both scenarios
        Logout::run(new Runner(CONFIG['steps']['logout'],$this->driver),$this->driver);
    }

    private function driverQuit()
    {
        if ($this->driver !== null) {
            if (CONFIG['debug']) {
                Output::print('DEBUG', 'Closing the browser.');
            }
            // close the browser
            $this->driver->quit();
        }
    }

    public function __destruct()
    {
        $this->driverQuit();
    }
}