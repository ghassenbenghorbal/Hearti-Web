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
        $data = Patient::where('doctor_id', \Auth::user()->id)->when($request->sort_by, function ($query, $value) {
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
            'user_id' => 'exists:users,id',
            'doctor_id' => 'exists:users,id',
            'bracelet_url' => 'required',
            
            'relative_contact' => 'nullable',
            'relative_name' => 'nullable',
            'age' => 'nullable',
            'address' => 'nullable',
        ]);
        $patient = Patient::where('user_id',$request->user_id)->first();
        if($patient){
            Patient::update($data);
        }
        else{
            Patient::create($data);
        }
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
            'user_id' => 'exists:users,id',
            'doctor_id' => 'exists:users,id',
            'bracelet_url' => 'required',

            'relative_contact' => 'nullable',
            'relative_name' => 'nullable',
            'age' => 'nullable',
            'address' => 'nullable',
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

    public function updateBracelet (Request $request)
    {
        $request->validate([
            'bracelet_url' => ['required', 'url'],
            'secret_phrase' => ['required']
        ]);
        \Auth::user()->patient()->update($request->only('bracelet_url', 'secret_phrase'));
        return back()->with('success', "Bracelet information changed successfully!");
    }

    public function getPatientUsers(){
        // $id = auth()->user()->id;
        // $result = Patient::where('doctor_id', Auth::user()->id)->get(); 
        $results = User::with('patient')->get();
        return $results;
    }
}
