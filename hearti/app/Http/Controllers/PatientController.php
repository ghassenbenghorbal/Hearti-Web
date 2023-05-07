<?php

namespace App\Http\Controllers;

use App\Models\Patient;
use App\Models\User;
use Illuminate\Http\Request;
use Inertia\Inertia;

class PatientController extends Controller
{
    public function index(Request $request)
    {
        $data = Patient::when($request->sort_by, function ($query, $value) {
                $query->orderBy($value, request('order_by', 'asc'));
            })
            ->when(!isset($request->sort_by), function ($query) {
                $query->latest();
            })
            ->when($request->search, function ($query, $value) {
                $query->where('name', 'LIKE', '%'.$value.'%');
            })
            ->paginate($request->page_size == -1 ? 1000 : $request->page_size);
        return Inertia::render('patient/index', [
            'items' => $data,
        ]);
    }

    public function store(Request $request)
    {   
        $data = $this->validate($request, [
            'secret_phrase' => 'required',
            'name' => 'required',
            'relative_name' => 'required',
            'relative_contact' => 'required',
            'age' => 'required',
            'address' => 'required|string',
            'user_id' => 'exists:users,id',
            'doctor_id' => 'exists:users,id',
            'bracelet_url' => 'required',
        ]);
        Patient::create($data);
        return redirect()->back()->with('message', [
            'type' => 'success',
            'text' => 'Success create patient!',
        ]);
    }

    public function update($id, Request $request)
    {
        $patient = Patient::findOrFail($id);
        $data = $this->validate($request, [
            'secret_phrase' => 'required',
            'name' => 'required',
            'relative_name' => 'required',
            'relative_contact' => 'required',
            'age' => 'required',
            'address' => 'required|string',
            'user_id' => 'exists:users,id',
            'doctor_id' => 'exists:users,id',
            'bracelet_url' => 'required',
        ]);
        $patient->update($data);
        return redirect()->back()->with('message', [
            'type' => 'success',
            'text' => 'Success edit patient!',
        ]);
    }

    public function destroy($id)
    {   
        $patient = Patient::findOrFail($id);
        $patient->delete();
        return redirect()->back()->with('message', [
            'type' => 'success',
            'text' => 'Success delete patient!',
        ]);
    }

    public function getPatientUsers(){
        // $id = auth()->user()->id;
        // $result = Patient::where('doctor_id', Auth::user()->id)->get(); 
        $results = User::all();
        return $results;
    }
}
