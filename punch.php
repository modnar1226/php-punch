<?php
require_once __DIR__ . '/src/config.php';

use Punch\Output;
use Punch\PunchTheClock;
use Symfony\Component\Process\Process;


function command_exists($command_name)
{
    $test_method = (false === stripos(PHP_OS, 'win')) ? 'command -v' : 'where';
    return (null === shell_exec("$test_method $command_name")) ? false : true;
}
/** -------------------------------------------------------------------------------- */
$command = 'chromedriver';
if (command_exists($command)) {
    $process = new Process([$command]);

    try {
        $process->start();
        if ($process->isRunning()) {
            
            new PunchTheClock();
            $process->stop();
        } else {
            sleep(1);
            Output::print(
                'ERROR',
                'The ' . $command . ' command did not start.'
            );
        }
    } catch (\Throwable $th) {
        // log the error in the timesheet file
        Output::print(
            'ERROR',
            $th->getMessage()
        );
    }
} else {
    // log the command doesn't exist error message
    Output::print(
        'ERROR',
        'The ' . $command . ' command does not exist on this system. Make sure it is installed and available on your system path.'
    );
}

