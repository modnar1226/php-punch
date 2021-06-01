<?php

use Punch\PunchTheClock;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Process\Process;

final class PunchTheClockTest extends TestCase
{
    private static $process = null;

    public static function setUpBeforeClass(): void
    {
        self::$process = new Process(['chromedriver']);
        self::$process->start();
    }

    public function testPunchIn()
    {
        // simulate command line user input
        $_SERVER['argv'][0] = 'PunchTheClockTest.php';
        $_SERVER['argv'][1] = 'in'; // punch direction
        $_SERVER['argv'][2] = 1; // wait time
        new PunchTheClock();
        $this->assertTrue(true); // making sure we make it to this point for now
    }

    public function testPunchOut()
    {
        // simulate command line user input
        $_SERVER['argv'][0] = 'PunchTheClockTest.php';
        $_SERVER['argv'][1] = 'out'; // punch direction
        $_SERVER['argv'][2] = 1; // wait time
        new PunchTheClock();
        $this->assertTrue(true); // making sure we make it to this point for now
    }

    public static function tearDownAfterClass(): void
    {
        self::$process->stop();

    }
}