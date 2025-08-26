<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Supervisor;
use Illuminate\Http\Request;

class SupervisorController extends Controller
{
    public function index(Request $request)
    {
        $supervisors = Supervisor::with('teacher.user')->get();
        return response()->json($supervisors);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'teacher_id' => 'required|integer|exists:teachers,id',
            'max_students' => 'required|integer|min:1',
            'expertise' => 'nullable|string'
        ]);
        $supervisor = Supervisor::create($data);
        return response()->json($supervisor->load('teacher'),201);
    }

    public function show(Supervisor $supervisor)
    {
        return response()->json($supervisor->load('teacher.user'));
    }

    public function update(Request $request, Supervisor $supervisor)
    {
        $data = $request->validate([
            'teacher_id' => 'sometimes|integer|exists:teachers,id',
            'max_students' => 'sometimes|integer|min:1',
            'expertise' => 'nullable|string'
        ]);
        $supervisor->update($data);
        return response()->json($supervisor->load('teacher'));
    }

    public function destroy(Supervisor $supervisor)
    {
        $supervisor->delete();
        return response()->json(['message'=>'Deleted']);
    }
}
