<?php

use PHPUnit\Framework\TestCase;

class CalculateDueDateTest extends TestCase
{
    private $calculateDueDate;

    public function setUp()
    {
        $this->calculateDueDate = new CalculateDueDate;
    }

    public function testCalculateSubmitedDateTimeParamShouldBeWorkDay()
    {
        $this->expectException(InvalidArgumentException::class);

        $submitedDateTime = new DateTime('2020-02-29T09:00');
        $turnaroundHours = 1;

        $this->calculateDueDate->calculate($submitedDateTime, $turnaroundHours);
    }

    public function testCalculateSubmitedDateTimeParamShouldBeWorkHour()
    {
        $this->expectException(InvalidArgumentException::class);

        $submitedDateTime = new DateTime('2020-02-28T06:00');
        $turnaroundHours = 1;

        $this->calculateDueDate->calculate($submitedDateTime, $turnaroundHours);
    }

    public function testCalculateTurnaroundHoursParamShouldBePositiveAndNotZero()
    {
        $this->expectException(InvalidArgumentException::class);

        $submitedDateTime = new DateTime('2020-02-28T09:00');
        $turnaroundHours = -1;

        $this->calculateDueDate->calculate($submitedDateTime, $turnaroundHours);
    }

    public function testCalculateDueDateShouldBeOnDay()
    {
        $submitedDate = new DateTime('2020-02-27T10:00');
        $turnaroundHours = 4;
        $dueDateExpected = new DateTime('2020-02-27T14:00');

        $dueDate = $this->calculateDueDate->calculate($submitedDate, $turnaroundHours);

        $this->assertEquals($dueDateExpected, $dueDate);
    }

    public function testCalculatDueDateShouldBeNextDay()
    {
        $submitedDate = new DateTime('2020-02-27T09:00');
        $turnaroundHours = 8;
        $dueDateExpected = new DateTime('2020-02-28T09:00');

        $dueDate = $this->calculateDueDate->calculate($submitedDate, $turnaroundHours);

        $this->assertEquals($dueDateExpected, $dueDate);
    }

    public function testCalculateDueDateShouldBeNextWeek()
    {
        $submitedDate = new DateTime('2020-02-28T13:00');
        $turnaroundHours = 12;
        $dueDateExpected = new DateTime('2020-03-03T09:00');

        $dueDate = $this->calculateDueDate->calculate($submitedDate, $turnaroundHours);

        $this->assertEquals($dueDateExpected, $dueDate);
    }

}
