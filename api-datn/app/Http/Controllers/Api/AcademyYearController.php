<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\AcademyYear;
use Illuminate\Http\Request;

class AcademyYearController extends Controller
{
    public function index(Request $request)
    {
        $years = AcademyYear::withCount('project_terms')
            ->latest('id')
            ->paginate(15)
            ->appends($request->query());
        return response()->json($years);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'year_name' => 'required|string|max:255',
        ]);
        $year = AcademyYear::create($data);
        return response()->json($year, 201);
    }

    public function show(AcademyYear $academy_year)
    {
        return response()->json($academy_year->load('project_terms'));
    }

    public function update(Request $request, AcademyYear $academy_year)
    {
        $data = $request->validate([
            'year_name' => 'sometimes|string|max:255',
        ]);
        $academy_year->update($data);
        return response()->json($academy_year);
    }

    public function destroy(AcademyYear $academy_year)
    {
        $academy_year->delete();
        return response()->json(['message' => 'Deleted']);
    }
}
