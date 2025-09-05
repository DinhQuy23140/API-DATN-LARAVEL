<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Web\UserController as WebUserController;
use App\Http\Controllers\Web\ProgressLogController as WebProgressLogController;
use App\Http\Controllers\Web\AttachmentController as WebAttachmentController;
use App\Http\Controllers\Web\AcademyYearController as WebAcademyYearController;
use App\Http\Controllers\Web\ProjectTermsController;
use App\Http\Controllers\Web\StageTimeLineController;
use App\Http\Controllers\Web\BatchStudentController as WebBatchStudentController;
use App\Http\Controllers\Web\SupervisorController as WebSupervisorController;
use App\Http\Controllers\Web\ProjectTermsController as WebProjectTermsController;
use App\Http\Controllers\Web\TeacherController as WebTeacherController;

// Guest (login)
Route::middleware('guest')->group(function () {
    Route::get('/login', [WebUserController::class, 'showLoginForm'])->name('web.auth.login');
    Route::post('/login', [WebUserController::class, 'login'])->name('web.auth.login.post');

    // Forgot password page
    Route::get('/forgot-password', function () {
        return view('login.forgot_pass_ui');
    })->name('web.auth.forgot');
});

// Authenticated
Route::middleware('auth')->group(function () {
    Route::post('/logout', [WebUserController::class, 'logout'])->name('web.auth.logout');
    // Lecturer UI pages
    Route::get('/teacher/overview', [WebUserController::class, 'showOverView'])->name('web.teacher.overview'); // trang tổng quan
    Route::get('/teacher/profile', [WebUserController::class, 'showProfile'])->name('web.teacher.profile');
    Route::get('/teacher/research', fn () => view('lecturer-ui.research'))->name('web.teacher.research');
    Route::get('/teacher/students/{supervisorId}', [WebSupervisorController::class, 'getStudentBySupervisor'])->name('web.teacher.students');
    Route::get('/teacher/thesis-internship', fn () => view('lecturer-ui.thesis-internship'))->name('web.teacher.thesis_internship');
    Route::get('/teacher/thesis-rounds', fn () => view('lecturer-ui.thesis-rounds'))->name('web.teacher.thesis_rounds');
    // Optional: /teacher -> overview
    Route::get('/teacher', fn () => redirect()->route('web.teacher.overview'))->name('web.teacher.home');


    //login as admin
    Route::get('/admin/dashboard', fn () => view('admin-ui.dashboard'))->name('web.admin.overview');

    //login as assistant
    Route::get('/assistant/dashboard', fn () => view('assistant-ui.dashboard'))->name('web.assistant.overview');

    //login as head
    Route::get('/head/dashboard', fn () => view('head-ui.overview'))->name('web.head.overview');  
});

// Academy Years
Route::prefix('academy-years')->name('web.academy_years.')->group(function(){
    Route::get('/', [WebAcademyYearController::class, 'index'])->name('index');
    Route::get('/create', [WebAcademyYearController::class, 'create'])->name('create');
    Route::post('/', [WebAcademyYearController::class, 'store'])->name('store');
    Route::get('/{academy_year}', [WebAcademyYearController::class, 'show'])->name('show');
    Route::get('/{academy_year}/edit', [WebAcademyYearController::class, 'edit'])->name('edit');
    Route::put('/{academy_year}', [WebAcademyYearController::class, 'update'])->name('update');
    Route::delete('/{academy_year}', [WebAcademyYearController::class, 'destroy'])->name('destroy');
});

// Project Terms
Route::prefix('project-terms')->name('web.project_terms.')->group(function(){
    Route::get('/', [WebProjectTermsController::class, 'index'])->name('index');
    Route::get('/create', [WebProjectTermsController::class, 'create'])->name('create');
    Route::post('/', [WebProjectTermsController::class, 'store'])->name('store');
    Route::get('/{project_term}', [WebProjectTermsController::class, 'show'])->name('show');
    Route::get('/{project_term}/edit', [WebProjectTermsController::class, 'edit'])->name('edit');
    Route::put('/{project_term}', [WebProjectTermsController::class, 'update'])->name('update');
    Route::delete('/{project_term}', [WebProjectTermsController::class, 'destroy'])->name('destroy');
});

// Batch Students
Route::prefix('batch-students')->name('web.batch_students.')->group(function(){
    Route::get('/', [WebBatchStudentController::class, 'index'])->name('index');
    Route::get('/create', [WebBatchStudentController::class, 'create'])->name('create');
    Route::post('/', [WebBatchStudentController::class, 'store'])->name('store');
    Route::get('/{batch_student}', [WebBatchStudentController::class, 'show'])->name('show');
    Route::get('/{batch_student}/edit', [WebBatchStudentController::class, 'edit'])->name('edit');
    Route::put('/{batch_student}', [WebBatchStudentController::class, 'update'])->name('update');
    Route::delete('/{batch_student}', [WebBatchStudentController::class, 'destroy'])->name('destroy');
});

// Users (có thể bọc middleware('auth') nếu cần)
Route::prefix('users')->name('web.users.')->group(function () {
    Route::get('/', [WebUserController::class, 'index'])->name('index');
    Route::get('/create', [WebUserController::class, 'create'])->name('create');
    Route::post('/', [WebUserController::class, 'store'])->name('store');
    Route::get('/{user}', [WebUserController::class, 'show'])->name('show');
    Route::get('/{user}/edit', [WebUserController::class, 'edit'])->name('edit');
    Route::put('/{user}', [WebUserController::class, 'update'])->name('update');
    Route::delete('/{user}', [WebUserController::class, 'destroy'])->name('destroy');
});

// Progress Logs + nested attachments
Route::prefix('progress-logs')->name('web.progress_logs.')->group(function () {
    Route::get('/', [WebProgressLogController::class, 'index'])->name('index');
    Route::get('/create', [WebProgressLogController::class, 'create'])->name('create');
    Route::post('/', [WebProgressLogController::class, 'store'])->name('store');
    Route::get('/{progress_log}', [WebProgressLogController::class, 'show'])->name('show');
    Route::get('/{progress_log}/edit', [WebProgressLogController::class, 'edit'])->name('edit');
    Route::put('/{progress_log}', [WebProgressLogController::class, 'update'])->name('update');
    Route::delete('/{progress_log}', [WebProgressLogController::class, 'destroy'])->name('destroy');

    Route::get('/{progress_log}/attachments/create', [WebAttachmentController::class, 'create'])->name('attachments.create');
    Route::post('/{progress_log}/attachments', [WebAttachmentController::class, 'store'])->name('attachments.store');
});

// Single attachment operations
Route::get('attachments/{attachment}/edit', [WebAttachmentController::class, 'edit'])->name('web.attachments.edit');
Route::put('attachments/{attachment}', [WebAttachmentController::class, 'update'])->name('web.attachments.update');
Route::delete('attachments/{attachment}', [WebAttachmentController::class, 'destroy'])->name('web.attachments.destroy');

// Root redirect theo trạng thái đăng nhập
Route::get('/', function () {
    return auth()->check()
        ? redirect()->route('web.teacher.overview')
        : redirect()->route('web.auth.login');
})->name('web.home');

// Map /home về dashboard
Route::get('/home', fn() => redirect()->route('web.teacher.overview'));

// Fallback: URL không khớp
Route::fallback(fn() => redirect()->route('web.auth.login'));

// Head UI
Route::middleware('auth')->prefix('head')->name('web.head.')->group(function () {
    Route::view('/overview', 'head-ui.overview')->name('overview');
    Route::view('/profile', 'head-ui.profile')->name('profile');
    Route::view('/research', 'head-ui.research')->name('research');
    Route::view('/students', 'head-ui.students')->name('students');
    Route::view('/thesis/internship', 'head-ui.thesis-internship')->name('thesis_internship');
    Route::view('/thesis/rounds', 'head-ui.thesis-rounds')->name('thesis_rounds');
});

Route::middleware('auth')->prefix('assistant')->name('web.assistant.')->group(function () {
    Route::view('/dash', 'assistant-ui.dashboard')->name('dashboard');
    Route::view('/manage_departments', 'assistant-ui.manage-departments')->name('manage_departments');
    Route::view('/manage_majors', 'assistant-ui.manage-majors')->name('manage_majors');
    Route::view('/manage_staffs', 'assistant-ui.manage-staff')->name('manage_staffs');
    Route::view('/assign_head', 'assistant-ui.assign-head')->name('assign_head');
    Route::get('/rounds', [WebProjectTermsController::class, 'loadRounds'])->name('rounds');
    Route::view('/round-detail', 'assistant-ui.round-detail')->name('round_detail');
    Route::get('/round-detail/{round_id}', [WebProjectTermsController::class, 'loadRoundDetail'])->name('round_detail');
    Route::get('/thesis/rounds', [ProjectTermsController::class, 'loadRounds'])->name('rounds');
    Route::get('/thesis/rounds/{round_id}', [ProjectTermsController::class, 'loadRoundDetail'])->name('round_detail');
    Route::post('/thesis/rounds', [ProjectTermsController::class, 'store'])->name('rounds.store');
});
