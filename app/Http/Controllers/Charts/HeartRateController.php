<?php

namespace App\Http\Controllers\Charts;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\HeartRate;
use App\Models\Patient;

class HeartRateController extends Controller
{

    public function index($id)
    {
        $patient = Patient::findOrFail($id);
        $heartRates = HeartRate::selectRaw('time as x, patient_id, heart_rate as y')->where('patient_id',$id)->get();
        return $heartRates;
    }

    public function store(Request $request)
    {
        $data = $this->validate($request,[
            'heart_rates' => 'required|array',
            'heart_rates.*.time' => 'required|date_format:Y-m-d H:i:s',
            'heart_rates.*.heart_rate' => 'required|numeric',
            'heart_rates.*.patient_id' => 'required|exists:patients,id',
        ]);
        foreach($data['heart_rates'] as $heartRate){
            HeartRate::create($heartRate);
        }
        return \response()->json([
            'status' => 'success',
        ]);
    }
}
