<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;        
use App\Models\PostponeProjectTerm;
use App\Models\PostponeProjectTermFile;
use Illuminate\Http\Request;

class PostponeProjectTermController extends Controller
{
    //
    public function store(Request $request)
    {
        // Validate the request data
        $validatedData = $request->validate([
            'assignment_id' => 'required|exists:assignments,id',
            'project_term_id' => 'required|exists:project_terms,id',
            'files.*' => 'file|mimes:pdf,doc,docx|max:2048', // Example file validation
            'note' => 'nullable|string',
        ]);

        // Create a new PostponeProjectTerm record
        $postponeProjectTerm = PostponeProjectTerm::create([
            'assignment_id' => $validatedData['assignment_id'],
            'project_term_id' => $validatedData['project_term_id'],
            'status' => 'pending', // Default status
            'note' => $validatedData['note'] ?? null,

        ]);
        return response()->json($postponeProjectTerm, 201);
    }

    public function destroy(PostponeProjectTerm $postpone_project_term)
    {
        $postpone_project_term->delete();
        return response()->json(['message' => 'Deleted successfully'], 200);
    }

}
