<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\Employee;

class HomeController extends Controller
{
    public function index()
    {
        $patients = Employee::all();
        return Inertia::render('home', [
            'patients_' => $patients,
        ]);
    }
}
