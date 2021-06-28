<?php

use PHPUnit\Framework\TestCase;
use Punch\PaidTimeOff;
use Punch\Skipable;

class PaidTimeOffTest extends TestCase
{
    public function testSet()
    {
        $pto = PaidTimeOff::set(date('d-m-Y'));
        $this->assertInstanceOf(Skipable::class, $pto);
        return $pto;
    }

    /**
     * @depends testSet
     */
    public function testRead(PaidTimeOff $pto)
    {
        $this->assertTrue($pto::$date === date('d-m-Y'));
        $this->assertTrue($pto::$label === 'Paid Time Off');
    }
}
