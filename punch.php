<?php
require_once __DIR__ . '/src/config.php';

use \Punch\PunchTheClock;
use Symfony\Component\Process\Process;

$process = new Process(['chromedriver']);
$process->start();

new PunchTheClock();

$process->stop();