<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Marjor;
use Illuminate\Http\Request;

class MarjorController extends Controller
{
    //
    public function loadMajor() {
        $majors = Marjor::with('faculties', 'students')->get();
        return response()->view('assistant-ui.manage-majors', compact('majors'));
    }
}
