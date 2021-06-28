<?php

use PHPUnit\Framework\TestCase;
use Punch\Holiday;
use Punch\Skipable;

class HolidayTest extends TestCase
{
    public function testSet()
    {
        $holiday = Holiday::set(date('d-m-Y'), 'Holiday');
        $this->assertInstanceOf(Skipable::class, $holiday);
        return $holiday;
    }

    /**
     * @depends testSet
     */
    public function testRead(Holiday $holiday)
    {
        $this->assertTrue($holiday::$date === date('d-m-Y'));
        $this->assertTrue($holiday::$label === 'Holiday');
    }
}
