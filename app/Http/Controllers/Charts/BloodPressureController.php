<?php

namespace App\Http\Controllers\Charts;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\BloodPressure;
use App\Models\User;

class BloodPressureController extends Controller
{
    public function index($id)
    {
        $user = User::findOrFail($id);
        $bloodPressures = BloodPressure::selectRaw('time as x, user_id, blood_pressure as y')->where('user_id',$id)->get();
        return $bloodPressures;
    }

    public function store(Request $request)
    {
        $data = $this->validate($request,[
            'blood_pressures' => 'required|array',
            'blood_pressures.*.time' => 'required|date_format:Y-m-d H:i:s',
            'blood_pressures.*.blood_pressure' => 'required|numeric',
            'blood_pressures.*.user_id' => 'required|exists:users,id',
        ]);
        foreach($data['blood_pressures'] as $bloodPressure){
            BloodPressure::create($bloodPressure);
        }
        return \response()->json([
            'status' => 'success',
        ]);
    }
}
