<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\PostponeProjectTermFile;
use Illuminate\Http\Request;

class PostponeProjectTermFileController extends Controller
{
    //
    public function store(Request $request)
    {
        // Validate the request data
        $validatedData = $request->validate([
            'postpone_project_term_id' => 'required|exists:postpone_project_terms,id',
            'file_path' => 'required|string',
            'file_name' => 'required|string',
            'file_type' => 'required|string',
        ]);
        $result = PostponeProjectTermFile::create($validatedData);
        return response()->json($result, 201);
    }
}
