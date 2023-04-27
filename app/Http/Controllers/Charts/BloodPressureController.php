<?php

namespace App\Http\Controllers\Charts;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\BloodPressure;
use App\Models\Patient;

class BloodPressureController extends Controller
{
    public function index($id)
    {
        $patient = Patient::findOrFail($id);
        $bloodPressures = BloodPressure::selectRaw('time as x, patient_id, blood_pressure as y')->where('patient_id',$id)->get();
        return $bloodPressures;
    }

    public function store(Request $request)
    {
        $data = $this->validate($request,[
            'blood_pressures' => 'required|array',
            'blood_pressures.*.time' => 'required|date_format:Y-m-d H:i:s',
            'blood_pressures.*.blood_pressure' => 'required|numeric',
            'blood_pressures.*.patient_id' => 'required|exists:patients,id',
        ]);
        foreach($data['blood_pressures'] as $bloodPressure){
            BloodPressure::create($bloodPressure);
        }
        return \response()->json([
            'status' => 'success',
        ]);
    }
}
