<?php

namespace App\Http\Controllers;

use App\Services\HolidayService;
use Illuminate\Http\Request;

class HolidayController extends Controller
{
    private $holidayService;

    public function __construct (HolidayService $holidayService)
    {
        $this->holidayService = $holidayService;
    }

    public function index ()
    {
        return view('holidays');
    }

    public function store (Request $request)
    {
        $this->holidayService->validate($request);
        return $this->holidayService->checkDateForHolidays($request);
    }
}
