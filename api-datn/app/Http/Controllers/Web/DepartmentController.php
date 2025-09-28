<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Department;
use Illuminate\Http\Request;

class DepartmentController extends Controller
{
    //
    public function loadDepartments() {
        $departments = Department::with('departmentRoles', 'teachers', 'subjects')->get();
        return response()->view('assistant-ui.manage-departments', compact('departments'));
    }
}
