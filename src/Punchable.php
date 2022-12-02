<?php
namespace Punch;

use Punch\Holiday;
use Punch\PaidTimeOff;
use Symfony\Component\Yaml\Yaml;

abstract class Punchable
{
    const IN = array(
        'in', 'In', 'IN', 'en', 'En', 'EN'
    );

    const OUT = array(
        'out', 'Out', 'OUT', 'fuera', 'Fuera', 'FUERA'
    );

    abstract protected function punch($direction);

    protected function isTodayAHoliday(Holiday $holiday)
    {
        if ($holiday instanceof Skipable) {
            return date('d-m-Y') === $holiday::$date;
        } else {
            throw new \Exception("The object Holiday must interface " . Skipable::class, 1);
        }
    }

    protected function isUsingPaidTImeOff(PaidTimeOff $pto)
    {
        if ($pto instanceof Skipable) {
            return date('d-m-Y') === $pto::$date;
        } else {
            throw new \Exception("The object Holiday must interface " . Skipable::class, 1);
        }
    }

    protected function validate($argv)
    {
        if (empty($argv[1])) {
            throw new \Exception("You must provide a direction in which to punch! (In or Out?)", 1);
        }

        if (!in_array($argv[1], array_merge(self::IN, self::OUT))) {
            throw new \Exception("Invalid punch direction!", 1);
        }

        if ((!empty($argv[2])) && !is_numeric($argv[2])) {
            throw new \Exception("Invalid time to wait value, it must be numeric!", 1);
        }
    }

    // validate the yaml file and parse it into a CONFIG constant
    protected function buildConstants()
    {
        $filePath = dirname(__FILE__) . '/playbooks/' . PLAYBOOK . '.yaml';
        $validatorPath = dirname(dirname(__FILE__)) . '/validation/ValidatePlaybook.php --format json';
        if (file_exists($filePath)) {
            ob_start();
            passthru('php ' . $validatorPath . ' ' . $filePath,$result);
            $output = json_decode(ob_get_contents(),true);
            ob_end_clean();
            if ($output[0]['valid']) {
                define('CONFIG', Yaml::parseFile($filePath));
            }
        }
    }
}