<?php

namespace App\Services;

class HolidayService
{
    private $holidays;

    public function __construct ()
    {
        $this->holidays = [
            [
                'title' => '1st of January',
                'day' => 1,
                'day_of_week' => null,
                'number_of_week' =>null,
                'month'=>1
            ],
            [
                'title' => '7th of January',
                'day' => 7,
                'day_of_week' => null,
                'number_of_week' =>null,
                'month'=>1
            ],
            [
                'title' => 'From 1st of May till 7th of May',
                'day' => 1,
                'day_of_week' => null,
                'number_of_week' =>null,
                'month'=>5
            ],
            [
                'title' => 'From 1st of May till 7th of May',
                'day' => 2,
                'day_of_week' => null,
                'number_of_week' =>null,
                'month'=>5
            ],
            [
                'title' => 'From 1st of May till 7th of May',
                'day' => 3,
                'day_of_week' => null,
                'number_of_week' =>null,
                'month'=>5
            ],
            [
                'title' => 'From 1st of May till 7th of May',
                'day' => 4,
                'day_of_week' => null,
                'number_of_week' =>null,
                'month'=>5
            ],
            [
                'title' => 'From 1st of May till 7th of May',
                'day' => 5,
                'day_of_week' => null,
                'number_of_week' =>null,
                'month'=>5
            ],
            [
                'title' => 'From 1st of May till 7th of May',
                'day' => 6,
                'day_of_week' => null,
                'number_of_week' =>null,
                'month'=>5
            ],
            [
                'title' => 'From 1st of May till 7th of May',
                'day' => 7,
                'day_of_week' => null,
                'number_of_week' =>null,
                'month'=>5
            ],
            [
                'title' => 'Monday of the 3rd week of January',
                'day' => null,
                'day_of_week' => 1,
                'number_of_week' =>3,
                'month'=>1
            ],
            [
                'title' => 'Monday of the last week of March',
                'day' => null,
                'day_of_week' => 1,
                'number_of_week' =>-1,
                'month'=>4
            ],
            [
                'title' => 'Thursday of the 4th week of November',
                'day' => null,
                'day_of_week' => 4,
                'number_of_week' =>4,
                'month'=>11
            ]
        ];
    }

    private function getWeekOfMonth($date)
    {
        $lastOfMonth = strtotime(date("Y-m-t", $date));
        $firstOfMonth = strtotime(date("Y-m-01", $date));

        $weekNumber['number'] = intval(date("W", $date)) - intval(date("W", $firstOfMonth)) + 1;
        $lastWeekNumber = intval(date("W", $lastOfMonth)) - intval(date("W", $firstOfMonth)) + 1;

        $weekNumber['isLast'] = false;
        if ($weekNumber['number'] == $lastWeekNumber) {
            $weekNumber['isLast'] = true;
            return $weekNumber;
        } else return $weekNumber;
    }

    private function changeDateFormat ($date)
    {
        $weekNumber = $this->getWeekOfMonth($date);
        $formatedDate = [
            'day' => date('j', $date),
            'day_of_week' => date('N', $date),
            'number_of_week' =>$weekNumber,
            'month'=>date('n', $date)
        ];
        return $formatedDate;
    }

    private function compareDates ($date1, $date2)
    {
        if ($date1['month'] != $date2['month']) return false;
        $isCorrectDate = $date1['day_of_week'] == $date2['day_of_week'] &&
            ($date1['number_of_week']['number'] == $date2['number_of_week'] ||
                $date1['number_of_week']['isLast']);
        if ($date1['day'] == $date2['day'] || $isCorrectDate) return $date2['title'];
    }

    private function isWeekend ($date)
    {
        return date('N', $date) >= 6 ? 'It is weekends!': null;
    }

    private function isHoliday ($date)
    {
        foreach ($this->holidays as $holiday) {
            $result = $this->compareDates($date, $holiday);
            if ($result) return $result.' holidays!';
        }
        return null;
    }
    private function isMondayAfterHoliday ($date, $request)
    {
        if ($date['day_of_week'] != 1) return null;
        $sunday = strtotime($request. " -1 day");
        $saturday = strtotime($request. " -2 day");
        $formatedSunday = $this->changeDateFormat($sunday);
        $formatedSaturday = $this->changeDateFormat($saturday);
        return $this->isHoliday($formatedSunday) || $this->isHoliday($formatedSaturday) ?
            'It is day-off because of holidays!' : null;
    }

    public function checkDate ($request)
    {
        $date = strtotime($request);
        $formatedDate = $this->changeDateFormat($date);
        return $this->isHoliday($formatedDate) ?? $this->isMondayAfterHoliday($formatedDate, $request) ?? $this->isWeekend($date) ?? 'It is working day!';
    }
}