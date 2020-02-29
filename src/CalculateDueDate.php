<?php

class CalculateDueDate
{
    const WORK_HOUR_START = 9;
    const WORK_HOUR_END = 17;
    const INVALID_SUBMITED_DATETIME_MSG = 'A problem can only be reported during working hours.';
    const INVALID_TURNAROUND_HOURS_MSG = 'The turnaround time must be defined in working hours.';

    public function calculate(DateTime $submitedDateTime, int $turnaroundHours) : DateTime
    {
        $dueDateTime = clone $submitedDateTime;

        $this->validateParams($submitedDateTime, $turnaroundHours);

        $elapsedWorkHoursAtSubmitedDay = intval($submitedDateTime->format('G')) - self::WORK_HOUR_START;
        $workHoursFromStartOfSubmitedDayToDueDate = $elapsedWorkHoursAtSubmitedDay + $turnaroundHours;
        $daysToDueDate = intdiv($workHoursFromStartOfSubmitedDayToDueDate, $this->getWorkHoursADay());
        $remainingHoursToDueDate = $workHoursFromStartOfSubmitedDayToDueDate % $this->getWorkHoursADay();

        $dueDateTime->modify('+' . $daysToDueDate . ' weekdays');
        $dueDateTime->modify('+' . (self::WORK_HOUR_START + $remainingHoursToDueDate) . ' hours');

        return $dueDateTime;
    }

    private function validateParams(DateTime $submitedDateTime, int $turnaroundHours) : void
    {
        $this->validateSubmitedDateTime($submitedDateTime);
        $this->validateTurnaroundHours($turnaroundHours);
    }

    private function validateSubmitedDateTime(DateTime $submitedDateTime) : void
    {
        if ($this->isSubmitedDateTimeOnWeekend($submitedDateTime) ||
            $this->isSubmitedDateTimeOutOfWorkingHours($submitedDateTime)) {
            throw new InvalidArgumentException(self::INVALID_SUBMITED_DATETIME_MSG);
        }
    }

    private function isSubmitedDateTimeOnWeekend(DateTime $submitedDateTime) : bool
    {
        return $submitedDateTime->format('N') > 5;
    }

    private function isSubmitedDateTimeOutOfWorkingHours(DateTime $submitedDateTime) : bool
    {
        $submitedHour = $submitedDateTime->format('G');
        return $submitedHour < self::WORK_HOUR_START || $submitedHour >= self::WORK_HOUR_END;
    }

    private function validateTurnaroundHours(int $turnaroundHours) : void
    {
        if ($this->isTurnaroundHoursNegativeOrZero($turnaroundHours)) {
            throw new InvalidArgumentException(self::INVALID_TURNAROUND_HOURS_MSG);
        }
    }

    private function isTurnaroundHoursNegativeOrZero(int $turnaroundHours) : bool
    {
        return $turnaroundHours < 1;
    }

    private function getWorkHoursADay()
    {
        return self::WORK_HOUR_END - self::WORK_HOUR_START;
    }

}
