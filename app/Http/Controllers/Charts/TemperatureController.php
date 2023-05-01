<?php

namespace App\Http\Controllers\Charts;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Temperature;
use App\Http\Resources\TemperatureResource;
use App\Models\User;

class TemperatureController extends Controller
{
    public function index($id)
    {
        $user = User::findOrFail($id);
        $temperatures = Temperature::selectRaw('time as x, user_id, temperature as y')->where('user_id',$id)->get();
        return $temperatures;
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $data = $this->validate($request,[
            'temperatures' => 'required|array',
            'temperatures.*.time' => 'required|date_format:Y-m-d H:i:s',
            'temperatures.*.temperature' => 'required|numeric',
            'temperatures.*.user_id' => 'required|exists:users,id',
        ]);
        foreach($data['temperatures'] as $temperature){
            Temperature::create($temperature);
        }
        return \response()->json([
            'status' => 'success',
        ]);

    }

}
