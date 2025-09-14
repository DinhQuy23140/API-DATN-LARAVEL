<?php

use App\Http\Controllers\API\ReportFilesController;
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

//progress-log
Route::apiResource('progress-logs', ProgressLogController::class);
Route::get('/progress-logs/project/{projectId}', [ProgressLogController::class, 'getProjectLogByIdProject']);
Route::apiResource('progress-logs.attachments', AttachmentController::class)->shallow();

//attachments
Route::get('/all-attachments', [AttachmentController::class, 'getAllAttachment']);

//user
Route::apiResource('users', UsersController::class);

//auth
Route::post('auth/login', [UsersController::class, 'login']);
Route::middleware('auth:sanctum')->post('auth/logout', [UsersController::class, 'logout']);

//project
Route::apiResource('projects', ProjectController::class);
Route::post('create_project', [ProjectController::class, 'store']);

//assignment
Route::apiResource('assignments', AssignmentController::class);
Route::get('/assignments/student/{studentId}', [AssignmentController::class, 'getAssignmentByStudentId']);
Route::get('/assignments/student/{studentId}/project-term/{projectTermId}', [AssignmentController::class, 'getAssignmentByStudentIdAndProjectTermId']);
Route::patch('/assignments/{assignmentId}/project/{projectId}', [AssignmentController::class, 'updateProjectIdAssignmentByAssIdAndProId']);

//student
Route::apiResource('students', StudentController::class);

//supervisor
Route::apiResource('supervisors', SupervisorController::class);
Route::get('/supervisors/project-term/{projectTermId}', [SupervisorController::class, 'getSupervisorsByProjectTerm']);

//teacher
Route::apiResource('teachers', TeacherController::class);

//academy-year
Route::apiResource('academy-years', AcademyYearController::class);

//project-terms
Route::apiResource('project-terms', ProjectTermsController::class);
Route::get('/project-terms/student/{studentId}', [ProjectTermsController::class, 'getProjectTermbyStudentId']);

//batch-students
Route::apiResource('batch-students', BatchStudentController::class);

//assignment-supervisor
Route::get('/assignment-supervisors', [App\Http\Controllers\Api\AssignmentSupervisorController::class, 'index']);
Route::post('/assignment-supervisors', [App\Http\Controllers\Api\AssignmentSupervisorController::class, 'store']);

//attachment
Route::get('attachment/progress/{progressId}', [AttachmentController::class, 'getAttacahmentByProgressLogId']);
Route::post('attachment/create/{progressId}', [AttachmentController::class, 'crateListAssignmentByProgressId']);


//reportFile
Route::apiResource('report-files', ReportFilesController::class);
Route::get('/report-files/project/{projectId}/type/{typeReport}', [ReportFilesController::class, 'getReportFileByProjectAndType']);