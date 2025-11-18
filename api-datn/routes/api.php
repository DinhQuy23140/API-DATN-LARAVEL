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
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use App\Http\Controllers\Api\ProposedTopicController;
use App\Http\Controllers\Api\RegisterProjectTermController;


Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

//vertify email
// Gửi email xác minh
Route::post('/email/verification-notification', function (Request $request) {
    if ($request->user()->hasVerifiedEmail()) {
        return response()->json(['message' => 'Email already verified.']);
    }

    $request->user()->sendEmailVerificationNotification();

    return response()->json(['message' => 'Verification link sent!']);
})->middleware('auth:sanctum');

// Xác minh email
Route::get('/email/verify/{id}/{hash}', function (EmailVerificationRequest $request) {
    $request->fulfill(); // đánh dấu verified

    return response()->json(['message' => 'Email verified successfully.']);
})->middleware(['auth:sanctum', 'signed'])->name('verification.verify');

// Kiểm tra trạng thái
Route::get('/email/verified-status', function (Request $request) {
    return response()->json([
        'verified' => $request->user()->hasVerifiedEmail()
    ]);
})->middleware('auth:sanctum');

Route::post('auth/register', [UsersController::class, 'register']);


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

//register project term
Route::apiResource('project-term-registrations', RegisterProjectTermController::class);
Route::get('/project-term-registrations/student/{studentId}', [RegisterProjectTermController::class, 'getRegisterProjectTermByStudentId']);
//project
Route::apiResource('projects', ProjectController::class);
Route::post('assignments/{assignmentId}/project', [ProjectController::class, 'updateOrCreateProject']);

//assignment
Route::apiResource('assignments', AssignmentController::class);
Route::get('/assignments/student/{studentId}', [AssignmentController::class, 'getAssignmentByStudentId']);
Route::get('/assignments/student/{studentId}/project-term/{projectTermId}', [AssignmentController::class, 'getAssignmentByStudentIdAndProjectTermId']);
Route::patch('/assignments/{assignmentId}/project/{projectId}', [AssignmentController::class, 'updateProjectIdAssignmentByAssIdAndProId']);
Route::get('/assignment/recent/student/{studentId}', [AssignmentController::class, 'getRecentAssignmentByStudentId']);
Route::get('/assignment/{assignmentId}', [AssignmentController::class, 'getAssignmentById']);
Route::get('/assignments/teacher/{teacherId}', [AssignmentController::class, 'getAssignmentByTeacherId']);

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
Route::get('/project_terms/new_terms', [ProjectTermsController::class, 'getNewProjectTerm']);

//batch-students
Route::apiResource('batch-students', BatchStudentController::class);

//assignment-supervisor
Route::get('/assignment-supervisors', [App\Http\Controllers\Api\AssignmentSupervisorController::class, 'index']);
Route::post('/assignment-supervisors', [App\Http\Controllers\Api\AssignmentSupervisorController::class, 'store']);
Route::get('/assignment-supervisors/teacher/{teacherId}', [App\Http\Controllers\Api\AssignmentSupervisorController::class, 'getAssignmentSupervisorsByTeacherId']);

//attachment
Route::get('attachment/progress/{progressId}', [AttachmentController::class, 'getAttacahmentByProgressLogId']);
Route::post('attachment/create/{progressId}', [AttachmentController::class, 'crateListAssignmentByProgressId']);
Route::post('attachment/create-general', [AttachmentController::class, 'create']);


//reportFile
Route::apiResource('report-files', ReportFilesController::class);
Route::get('/report-files/project/{projectId}/type/{typeReport}', [ReportFilesController::class, 'getReportFileByProjectAndType']);

//proposed topic
Route::get('/proposed-topics/assignment/{assignmentId}', [ProposedTopicController::class, 'forAssignment']);