<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Marjor;
use Illuminate\Http\Request;

class MarjorController extends Controller
{
    //
    public function loadMajor() {
        $majors = Marjor::with('department', 'students')->get();
        return response()->view('assistant-ui.manage-majors', compact('majors'));
    }

    public function store(Request $request) {
        $data = $request->validate([
            'code' => 'required|string|max:100|unique:marjors,code',
            'name' => 'required|string|max:255',
            'description' => 'nullable|string'
        ]);

        $marjor = Marjor::create($data);

        return response()->json([
            'ok' => true,
            'data' => $marjor->load('faculties') // nếu muốn trả luôn dữ liệu quan hệ
        ]);
    }

    public function update(Request $request, $id) {
        $major = Marjor::findOrFail($id);
        $data = $request->validate([
            'code' => 'sometimes|string|max:100|unique:marjors,code,' . $major->id,
            'name' => 'sometimes|string|max:255',
            'description' => 'nullable|string'
        ]);
        $major->update($data);
        return response()->json([
            'ok' => true,
            'data' => $major->load('faculties') // nếu muốn trả luôn dữ liệu quan hệ
        ]);
    }

    public function delete($id) {
        $major = Marjor::findOrFail($id);
        $major->delete();
        return response()->json(['ok'=>true]);
    }
}
