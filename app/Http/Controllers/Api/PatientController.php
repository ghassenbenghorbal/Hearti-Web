<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Patient;
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
}
