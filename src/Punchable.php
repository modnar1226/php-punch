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
        $filePath = dirname(__FILE__) . '/steps/' . PLAYBOOK . '.yaml';
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

        $this->buildHolidays();
        $this->buildPaidTimeOffDays();
    }

    protected function buildHolidays()
    {
        $holidaysToSet = [];
        $availableHolidays = $this->availableHolidays();
        if (!empty(CONFIG['holidays'])) {
            foreach (CONFIG['holidays'] as $holiday) {
                if (in_array($holiday, array_keys($availableHolidays))) {
                    $holidaysToSet[$holiday] = $this->availableHolidays($holiday);
                }
            }
        }

        define('HOLIDAYS',$holidaysToSet);
    }

    public function availableHolidays($holiday = null)
    {
        $currentYear = date("Y");
        $dateFormat = 'd-m-Y';
        $independenceDayTime = strtotime("07/04/$currentYear");

        if (CONFIG['observedHolidays']) {
            // function to determine if forth of july is on a sunday.
            $isTheFourthOfJulyOnSunday = function ($time) {
                return ((int) date('N', $time) === 7);
            };

            if ($isTheFourthOfJulyOnSunday($independenceDayTime)) {
                $independenceDayTime = strtotime("07/05/$currentYear");
            }
        }

        $holidays = array(
            'New Years Day'    => date($dateFormat, strtotime("01/01/$currentYear")),
            'MLK Day'          => date($dateFormat, strtotime("third monday of January $currentYear")),
            'Memorial Day'     => date($dateFormat, strtotime("last monday of May $currentYear")),
            'Independence Day' => date($dateFormat, strtotime($independenceDayTime)),
            'Labor Day'        => date($dateFormat, strtotime("first monday of September $currentYear")),
            'Veterans Day'     => date($dateFormat, strtotime("11/11/$currentYear")),
            'Columbus Day'     => date($dateFormat, strtotime("second monday of October $currentYear")),
            'Thanksgiving Day' => date($dateFormat, strtotime("fourth thursday of November $currentYear")),
            'Christmas Day'    => date($dateFormat, strtotime("12/25/$currentYear"))
        );

        return ($holiday === null || empty($holidays[$holiday])) ? $holidays : $holidays[$holiday];
    }

    protected function buildPaidTimeOffDays()
    {
        $dateFormat = 'd-m-Y';

        $datesToSet = [];
        if (!empty(CONFIG['paidTimeOffDays'])) {
            foreach (CONFIG['paidTimeOffDays'] as $date) {
                $datesToSet[] = date($dateFormat, strtotime($date));
            }
        }

        define('PAID_TIME_OFF_DAYS', $datesToSet);
    }
}