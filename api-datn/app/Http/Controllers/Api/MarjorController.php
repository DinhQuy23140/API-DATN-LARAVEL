<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Marjor;
use Illuminate\Http\Request;

class MarjorController extends Controller
{
    //

    public function index()
    {
        $marjors = Marjor::all();
        return response()->json($marjors);
    }
}
