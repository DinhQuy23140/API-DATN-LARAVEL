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


Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::apiResource('progress-logs', ProgressLogController::class);

Route::apiResource('progress-logs.attachments', AttachmentController::class)->shallow();

Route::get('/all-attachments', [AttachmentController::class, 'getAllAttachment']);

Route::apiResource('users', UsersController::class);
Route::post('auth/login', [UsersController::class, 'login']);
Route::middleware('auth:sanctum')->post('auth/logout', [UsersController::class, 'logout']);
Route::apiResource('projects', ProjectController::class);
Route::apiResource('assignments', AssignmentController::class);
Route::apiResource('students', StudentController::class);
Route::apiResource('supervisors', SupervisorController::class);
Route::apiResource('teachers', TeacherController::class);

