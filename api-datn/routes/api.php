<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AttachmentController;
use App\Http\Controllers\Api\ProgressLogController;
use App\Http\Controllers\Api\UsersController;
use App\Http\Controllers\Api\ProjectController;
use App\Http\Controllers\Api\AssignmentController;
use App\Http\Controllers\Api\StudentController;
use App\Http\Controllers\Api\SupervisorController;
use App\Http\Controllers\Api\TeacherController;
use App\Http\Controllers\Api\AcademyYearController;
use App\Http\Controllers\Api\ProjectTermsController;
use App\Http\Controllers\Api\BatchStudentController;


Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::apiResource('progress-logs', ProgressLogController::class);
Route::get('/progress-logs/project/{projectId}', [ProgressLogController::class, 'getProjectLogByIdProject']);

Route::apiResource('progress-logs.attachments', AttachmentController::class)->shallow();

Route::get('/all-attachments', [AttachmentController::class, 'getAllAttachment']);

Route::apiResource('users', UsersController::class);
Route::post('auth/login', [UsersController::class, 'login']);
Route::middleware('auth:sanctum')->post('auth/logout', [UsersController::class, 'logout']);
Route::apiResource('projects', ProjectController::class);
Route::apiResource('assignments', AssignmentController::class);
Route::get('/assignments/student/{studentId}', [AssignmentController::class, 'getAssignmentByStudentId']);
Route::get('/assignments/student/{studentId}/project-term/{projectTermId}', [AssignmentController::class, 'getAssignmentByStudentIdAndProjectTermId']);
Route::apiResource('students', StudentController::class);
Route::apiResource('supervisors', SupervisorController::class);
Route::apiResource('teachers', TeacherController::class);
Route::apiResource('academy-years', AcademyYearController::class);
Route::apiResource('project-terms', ProjectTermsController::class);
Route::get('/project-terms/student/{studentId}', [ProjectTermsController::class, 'getProjectTermbyStudentId']);
Route::apiResource('batch-students', BatchStudentController::class);
Route::get('/supervisors/project-term/{projectTermId}', [SupervisorController::class, 'getSupervisorsByProjectTerm']);

Route::get('/assignment-supervisors', [App\Http\Controllers\Api\AssignmentSupervisorController::class, 'index']);
Route::post('/assignment-supervisors', [App\Http\Controllers\Api\AssignmentSupervisorController::class, 'store']);