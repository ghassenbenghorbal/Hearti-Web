<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Patient;
use App\Models\Message;
use Illuminate\Http\Request;

class PatientController extends Controller
{
    public function index(Request $request)
    {
        $this->validate($request, [
            'page_size' => 'nullable|integer',
            'page' => 'nullable|integer',
        ]);
        $result = null;
        if($request->page_size == -1) {
            $results = Patient::all();
        }
        else{
            $results = Patient::paginate(request('page_size', 10));
        }
        return response()->json([
            'properties' => [
                'page' => $results->currentPage(),
                'total' => $results->total(),
                'total_page' => $results->lastPage(),
                'page_size' => $results->perPage(),
                'has_more_pages' => $results->hasMorePages()
            ],
            'data' => $results->items(),
        ]);
    }
    
    public function getPatientUsers(){
        $id = auth()->user()->id;
        $patients = Message::where('sender', $id)
                            ->orWhere('receiver', $id)
                            ->select(\DB::raw("CASE 
                                            WHEN sender = ".$id." THEN receiver 
                                            WHEN receiver = ".$id." THEN sender 
                                            END AS user_id"))
                            ->join('users', function($join) use($id) {
                                $join->on('users.id', '=', \DB::raw("IF(messages.sender = ".$id.", messages.receiver, messages.sender)"));
                            })
                            ->distinct()
                            ->pluck('user_id');
        $results = Patient::whereNotIn('user_id', $patients)->get();
        return $results;
    }
}
