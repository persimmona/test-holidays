<?php

namespace App\Http\Controllers;

use App\Http\Requests\HolidayRequest;
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

    public function store (HolidayRequest $request)
    {
        $response = $this->holidayService->checkDate($request->get('date'));
        return redirect()->back()->with('response', $response);
    }
}
