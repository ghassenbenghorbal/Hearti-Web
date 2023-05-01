<?php

namespace App\Http\Controllers\Charts;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\HeartRate;
use App\Models\User;

class HeartRateController extends Controller
{

    public function index($id)
    {
        $user = User::findOrFail($id);
        $heartRates = HeartRate::selectRaw('time as x, user_id, heart_rate as y')->where('user_id',$id)->get();
        return $heartRates;
    }

    public function store(Request $request)
    {
        $data = $this->validate($request,[
            'heart_rates' => 'required|array',
            'heart_rates.*.time' => 'required|date_format:Y-m-d H:i:s',
            'heart_rates.*.heart_rate' => 'required|numeric',
            'heart_rates.*.user_id' => 'required|exists:users,id',
        ]);
        foreach($data['heart_rates'] as $heartRate){
            HeartRate::create($heartRate);
        }
        return \response()->json([
            'status' => 'success',
        ]);
    }
}
