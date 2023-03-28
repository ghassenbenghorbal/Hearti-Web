<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\Patient;

class HomeController extends Controller
{
    public function index()
    {
        $patients = Patient::all();
        return Inertia::render('home', [
            'patients_' => $patients,
        ]);
    }
}
