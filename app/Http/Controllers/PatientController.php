<?php

namespace App\Http\Controllers;

use App\Models\Patient;
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
        ]);
        Patient::create($data);
        return redirect()->back()->with('message', [
            'type' => 'success',
            'text' => 'Success create patient!',
        ]);
    }

    public function update(Patient $patient, Request $request)
    {
        $data = $this->validate($request, [
            'secret_phrase' => 'required',
            'name' => 'required',
            'relative_name' => 'required',
            'relative_contact' => 'required',
            'age' => 'required',
            'address' => 'required|string'
        ]);
        $patient->update($data);
        return redirect()->back()->with('message', [
            'type' => 'success',
            'text' => 'Success edit patient!',
        ]);
    }

    public function destroy(Patient $patient)
    {
        $patient->delete();
        Block::where('id', $patient->id)->delete();
        return redirect()->back()->with('message', [
            'type' => 'success',
            'text' => 'Success delete patient!',
        ]);
    }
}
