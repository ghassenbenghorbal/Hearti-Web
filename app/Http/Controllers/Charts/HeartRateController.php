<?php

namespace App\Http\Controllers\Charts;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class HeartRateController extends Controller
{
    public function index(Request $request)
    {
        $heart_rate = HeartRate::orderByDesc("created_at")->first();
        return response()->json([
            "status" => "success",
            "data" => $heart_rate
        ]);
    }

    public function store(Request $request)
    {
        HeartRate::create($request->heartRate);
        return response()->json([
            "status" => "success"
        ]);
    }
}
