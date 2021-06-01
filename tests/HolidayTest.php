<?php

use PHPUnit\Framework\TestCase;
use Punch\Holiday;
use Punch\Skipable;

class HolidayTest extends TestCase
{
    public function testSet()
    {
        $holiday = Holiday::set('Holiday', date('d-m-Y'));
        $this->assertInstanceOf(Skipable::class, $holiday);
        return $holiday;
    }

    /**
     * @depends testSet
     */
    public function testRead(Holiday $holiday)
    {
        $this->assertTrue($holiday::$label === 'Holiday');
        $this->assertTrue($holiday::$date === date('d-m-Y'));
    }
}
