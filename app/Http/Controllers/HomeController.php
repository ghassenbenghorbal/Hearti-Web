<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\Patient;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    public function index()
    {   
        $patients = Auth::user()->is_patient ? [Auth::user()->patient] : Patient::where('doctor_id', Auth::user()->id)->get();
        // $heartRateImage = asset(\Storage::url('images/heart-rate.jpg'));
        // $temperatureImage = asset(\Storage::url('images/temperature.jpg'));
        // $bloodPressureImage = asset(\Storage::url('images/blood-pressure.png'));
        // $overallImage = asset(\Storage::url('images/overall.png'));
        $heartRateImage = asset(\Storage::url('images/heart-rate.gif'));
        $temperatureImage = asset(\Storage::url('images/temperature.gif'));
        $bloodPressureImage = asset(\Storage::url('images/blood-pressure.gif'));
        $overallImage = asset(\Storage::url('images/overall.gif'));
        return Inertia::render('home', [
            'patients_' => $patients,
            'heartRateImage' => $heartRateImage,
            'temperatureImage' => $temperatureImage,
            'bloodPressureImage' => $bloodPressureImage,
            'overallImage' => $overallImage,
        ]);
    }
}
