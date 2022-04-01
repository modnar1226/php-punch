<?php
require_once __DIR__ . '/src/config.php';

use Punch\Output;
use Punch\PunchTheClock;

try {
    new PunchTheClock();
} catch (\Throwable $th) {
    Output::print('ERROR', $th->getMessage());
}


