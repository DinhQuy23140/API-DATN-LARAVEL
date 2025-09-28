<?php

namespace App\Http\Controllers;

use App\Models\Marjor;
use Illuminate\Http\Request;

class SubjectsController extends Controller
{
    //
    public function loadMajor() {
        $major = Marjor::with('faculties', 'students')->get();
        return response()->view('assistant-ui.manage-majors', compact('major'));
    }
}
