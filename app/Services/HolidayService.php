<?php

namespace App\Services;

use Illuminate\Support\Facades\Validator;

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
        } else
            return $weekNumber;
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

    private function isWeekend ($date)
    {
        return (date('N', $date) >= 6);
    }

    private function compareDates ($date1, $date2)
    {
        if ($date1['month'] == $date2['month']) {
            if ($date1['day'] == $date2['day']) {
                return $date2['title'];
            } elseif ($date1['day_of_week'] == $date2['day_of_week']&&
                ($date1['number_of_week']['number'] == $date2['number_of_week']||
                    $date1['number_of_week']['isLast'])) {
                return $date2['title'];
            }
        } else {
            return false;
        }
    }

    private function isHoliday ($date)
    {
        foreach ($this->holidays as $holiday) {
            $result = $this->compareDates($date, $holiday);
            if ($result) {
                return $result;
            }
        }
        return false;
    }

    public function validate ($request)
    {
        Validator::make($request->only('date'), [
            'date' => 'required|date',
        ])->validate();
    }

    public function checkDateForHolidays ($request)
    {
        $date = strtotime($request['date']);
        $formatedDate = $this->changeDateFormat($date);

        $result = $this->isHoliday($formatedDate);
        if($result) {
            return redirect()->back()->with('response', $result.' holidays!');
        } elseif ($formatedDate['day_of_week']==1) {
            $sunday = strtotime($request['date']. " -1 day");
            $saturday = strtotime($request['date']. " -2 day");
            $formatedSunday = $this->changeDateFormat($sunday);
            $formatedSaturday = $this->changeDateFormat($saturday);
            if ($this->isHoliday($formatedSunday)|| $this->isHoliday($formatedSaturday)) {
                return redirect()->back()->with('response', 'It is day-off because of holidays!');
            }
        } elseif ($this->isWeekend($date)) {
            return redirect()->back()->with('response','It is weekends!');
        } else {
            return redirect()->back()->with('response','It is working day!');
        }
    }
}