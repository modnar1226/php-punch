<?php
require_once __DIR__ . '/src/config.php';

use \Punch\PunchTheClock;
use Symfony\Component\Process\Process;

$process = new Process(['chromedriver']);
try {
    $process->start();
    new PunchTheClock();
    
    $process->stop();
} catch (\Throwable $th) {
    // log the error in the timesheet file
    echo '"ERROR","'. $th->getMessage() .'"' . "\n";
}