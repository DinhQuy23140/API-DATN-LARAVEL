<?php

use App\Http\Controllers\Web\CouncilProjectsController;
use App\Http\Controllers\Web\DepartmentController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Web\UserController as WebUserController;
use App\Http\Controllers\Web\UserResearchController as WebUserResearchController;
use App\Http\Controllers\Web\ProgressLogController as WebProgressLogController;
use App\Http\Controllers\Web\AttachmentController as WebAttachmentController;
use App\Http\Controllers\Web\AcademyYearController as WebAcademyYearController;
use App\Http\Controllers\Web\ProjectTermsController;
use App\Http\Controllers\Web\BatchStudentController as WebBatchStudentController;
use App\Http\Controllers\Web\SupervisorController as WebSupervisorController;
use App\Http\Controllers\Web\ProjectTermsController as WebProjectTermsController;
use App\Http\Controllers\Web\TeacherController as WebTeacherController;
use App\Http\Controllers\Web\AssignmentController as WebAssignmentController;
use App\Http\Controllers\Web\AssignmentSupervisorController as AssignmentSupervisorController;
use App\Http\Controllers\Web\ReportFilesController;
use App\Http\Controllers\Web\CouncilController;
use App\Http\Controllers\Web\CouncilController as WebCouncilController;
use App\Http\Controllers\Web\CouncilProjectsController as WebCouncilProjectController;
use App\Http\Controllers\Web\CouncilMembersController as WebCouncilMembersController;
use App\Http\Controllers\Web\FacultiesController as WebFacultiesController;
use App\Http\Controllers\Web\RegisterController;
use App\Http\Controllers\Web\DepartmentController as WebDepartmentController;
use App\Http\Controllers\Web\MarjorController;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use App\Http\Controllers\Web\EmailVerificationController;
use App\Http\Controllers\Web\DepartmentRolesController as WebDepartmentRolesController;
use App\Http\Controllers\Web\CouncilProjectDefencesController as WebCouncilProjectDefencesController;
use App\Http\Controllers\Web\ReportFilesController as WebReportFilesController;

// Chỉ cho guest
Route::middleware('guest')->group(function () {
    // Login
    Route::get('/login', [WebUserController::class, 'showLoginForm'])->name('web.auth.login');
    Route::post('/login', [WebUserController::class, 'login'])->name('web.auth.login.post');
    
    // Forgot password
    Route::get('/forgot-password', function () {
        return view('login.forgot_pass_ui');
    })->name('web.auth.forgot');
    
    // Register
    Route::get('/register', [RegisterController::class, 'showRegisterForm'])->name('register');
    Route::post('/register', [RegisterController::class, 'register'])->name('register.post');

    // (GỬ KHỎI ĐÂY) // Route::get('/verify-success', [RegisterController::class, 'showVerifySuccess'])->name('verification.success');
});

// Cho cả user đã đăng nhập (sau verify vẫn vào được)
Route::get('/verify-success', [RegisterController::class, 'showVerifySuccess'])->name('verification.success');

// ===== Nhóm xác thực email (sau khi đăng ký) =====
Route::middleware('auth')->group(function () {

    // Trang nhắc kiểm tra email
    Route::get('/email/verify', function () {
        return view('login.verify-email');
    })->name('verification.notice');

    // Người dùng click link trong email
    Route::get('/email/verify/{id}/{hash}', [EmailVerificationController::class, 'verify'])
        ->middleware(['signed','throttle:6,1'])
        ->name('verification.verify');

    // Gửi lại link
    Route::post('/email/verification-notification', [EmailVerificationController::class, 'resend'])
        ->middleware(['throttle:6,1'])
        ->name('verification.send');
});

// Ví dụ: nhóm route yêu cầu email đã verify
Route::middleware(['auth','verified'])->prefix('teacher')->name('web.teacher.')->group(function () {
    // đặt các route cho giảng viên ở đây
    // Route::get('/dashboard', ...);
});

// Logout (chỉ cho auth user)
Route::post('/logout', [WebUserController::class, 'logout'])
    ->middleware('auth')
    ->name('web.auth.logout');

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

// Mặc định: vào root hoặc /home đều về trang login
Route::redirect('/', '/login')->name('web.home');
Route::redirect('/home', '/login');

// Fallback: URL không khớp
Route::fallback(fn() => redirect()->to('/login'));

// Head UI
Route::middleware('auth')->prefix('head')->name('web.head.')->group(function () {
    Route::view('/overview', 'head-ui.overview')->name('overview');
    Route::get('/profile/{teacherId}', [WebTeacherController::class, 'loadProfile'])->name('profile');
    Route::view('/research', 'head-ui.research')->name('research');
    Route::view('/students', 'head-ui.students')->name('students');
    Route::view('/thesis/internship', 'head-ui.thesis-internship')->name('thesis_internship');
    Route::get('/thesis/rounds', [WebProjectTermsController::class, 'getAllProjectTerms'])->name('thesis_rounds');
    Route::get('/thesis/round-detail/{termId}', [WebProjectTermsController::class, 'loadHeadRoundDetail'])->name('thesis_round_detail');
    Route::get ('head/assign-students/department/{departmentId}/term/{termId}', [WebProjectTermsController::class, 'assignmentSupervisor'])->name('thesis_round_supervision');
    Route::post('head/assign-supervisors/bulk', [AssignmentSupervisorController::class, 'storeBulk'])
    ->name('assign_supervisors.bulk');
    Route::get('bind-review-lecturers/department/{departmentId}/term/{termId}', [WebProjectTermsController::class, 'getProjectTermBtId'])->name('blind_review_lecturers');
    Route::post('/blind-review/assign', [WebAssignmentController::class, 'assign'])->name('blind_review.assign');
});

Route::middleware('auth')->prefix('assistant')->name('web.assistant.')->group(function () {
    Route::get('/dash', [WebTeacherController::class, 'loadDashboardAssistant'])->name('dashboard');
    Route::get('/manage_departments', [WebDepartmentController::class, 'loadDepartments'])->name('manage_departments');
    Route::get('/manage_majors', [MarjorController::class, 'loadMajor'])->name('manage_majors');
    Route::get('/manage_staffs', [WebTeacherController::class, 'loadTeachers'])->name('manage_staffs');
    Route::get('/assign_head', [WebDepartmentRolesController::class, 'loadDepartmentRoles'])->name('assign_head');
    Route::get('/rounds', [WebProjectTermsController::class, 'loadRounds'])->name('rounds');
    Route::view('/round-detail', 'assistant-ui.round-detail')->name('round_detail');
    Route::get('/round-detail/{round_id}', [WebProjectTermsController::class, 'loadRoundDetail'])->name('round_detail');
    Route::get('/thesis/rounds', [ProjectTermsController::class, 'loadRounds'])->name('rounds');
    Route::get('/thesis/rounds/{round_id}', [ProjectTermsController::class, 'loadRoundDetail'])->name('round_detail');
    Route::post('/thesis/rounds', [ProjectTermsController::class, 'store'])->name('rounds.store');
    Route::get('/students/import_students/{termId}', [WebBatchStudentController::class, 'getStudentNotInProjectTerm'])->name('students_import');
    Route::get('/staffs/import_supervisors/{termId}', [WebSupervisorController::class, 'getSupervisorNotInProjectTerm'])->name('supervisors_import');
    Route::get('/students_detail/{termId}', [WebBatchStudentController::class, 'getAllBatchStudentsByTerm'])->name('students_detail');
    Route::get('/supervisors_detail/{termId}', action: [WebSupervisorController::class, 'getAllSupervisorsByTerm'])->name('supervisors_detail');
    Route::post('/batch-students/bulk', [WebAssignmentController::class,'storeBulk'])->name('batch_students.bulk_store');
    Route::post('/supervisors/bulk', [WebSupervisorController::class,'storeBulk'])->name('supervisors.bulk_store');
    Route::post('/councils', [CouncilController::class, 'store'])->name('councils.store');
    Route::post('/councils/{council}/roles', [CouncilController::class, 'updateRoles'])->name('councils.update_roles');
    Route::get('/terms/{term}/councils/roles', [WebCouncilController::class, 'getCouncilByTermId'])->name('councils.roles');
    Route::patch('/councils/{council}', [CouncilController::class, 'update'])->name('councils.update');
    Route::post('/councils/{council}/members/save', [CouncilController::class, 'saveMembers'])->name('councils.members.save');
    Route::patch('/councils/{council}/members', [CouncilController::class, 'updateMembers'])
        ->name('councils.members.update');
    Route::get ('/assistant-ui/council-assign-students/{termId}', [WebCouncilController::class, 'getCouncilAndAssignmentByTermId'])->name('council_assign_students');
    Route::post('/councils/{council}/assign-students', [WebCouncilProjectController::class, 'assignStudents'])
        ->name('councils.assign_students');
});

Route::middleware(['web','auth'])->prefix('admin')->name('web.admin.')->group(function () {
    Route::view('/overview', 'admin-ui.dashboard')->name('dashboard');
    Route::view('/manage_accounts', 'admin-ui.manage-accounts')->name('manage_accounts');
    Route::view('/manage_academy_years', 'admin-ui.manage-academy-years')->name('manage_academy_years');
    Route::view('/manage_projects', 'admin-ui.manage-projects')->name('manage_projects');
    Route::view('/manage_terms', 'admin-ui.manage-terms')->name('manage_terms');
    Route::get('/manage_assistants', [WebFacultiesController::class, 'getAssistants'])->name('manage_assistants');
    Route::get('/manage_faculties', [WebFacultiesController::class, 'load_dashboard'])->name('manage_faculties');
    Route::post('/faculties', [WebFacultiesController::class, 'store'])->name('faculties.store');
    Route::patch('/faculties/{faculty}', [WebFacultiesController::class, 'update'])->name('faculties.update');
    Route::delete('/faculties/{faculty}', [WebFacultiesController::class, 'destroy'])->name('faculties.destroy');
});

// Authenticated
Route::middleware('auth')->group(function () {
    // Lecturer UI pages
    Route::get('/teacher/overview', [WebUserController::class, 'showOverView'])->name('web.teacher.overview'); // trang tổng quan
    Route::get('/teacher/profile', [WebUserController::class, 'showProfile'])->name('web.teacher.profile');
    Route::get('/teacher/research', [WebUserController::class, 'loadResearch'])->name('web.teacher.research');
    // Create and delete user research entries
    Route::post('/teacher/user-research', [WebUserResearchController::class, 'store'])->name('web.teacher.user_research.store');
    Route::delete('/teacher/user-research/{user_research}', [WebUserResearchController::class, 'destroy'])->name('web.teacher.user_research.destroy');
    Route::get('/teacher/students/{teacherId}', [WebSupervisorController::class, 'getStudentBySupervisor'])->name('web.teacher.students');
    Route::get('/teacher/thesis-internship', fn () => view('lecturer-ui.thesis-internship'))->name('web.teacher.thesis_internship');
    Route::get('/teacher/thesis-rounds/{teacherId}', [WebProjectTermsController::class, 'getProjectTermByTeacherId'])->name('web.teacher.thesis_rounds');
    Route::get('/teacher/all-thesis-rounds/{teacherId}', [WebProjectTermsController::class, 'getAllProjectTermsByHead'])->name('web.teacher.all_thesis_rounds');
    Route::get('/teacher/thesis-round-detail/{termId}/supervisor/{supervisorId}', [WebProjectTermsController::class, 'getDetailProjectTermByTeacherId'])->name('web.teacher.thesis_round_detail');
    Route::get('/teacher/weekly-log-detail/{progressLogId}', [WebProgressLogController::class, 'getProgressLogById'])->name('web.teacher.weekly_log_detail');
    //thesis round detail
    
    //stage 1 
    Route::get('/teacher/requests_management/{supervisorId}/term/{termId}', [AssignmentSupervisorController::class, 'getRequestManagementPage'])->name('web.teacher.requests_management');
    Route::get('/teacher/proposed_topic/{supervisorId}', [AssignmentSupervisorController::class, 'getProposeBySupervisor'])->name('web.teacher.proposed_topic');
    Route::get('/teacher/student_supervisor_term/{supervisorId}/term/{termId}', [AssignmentSupervisorController::class, 'getStudentBySupervisorAndTermId'])->name('web.teacher.student_supervisor_term');
    // Optional: /teacher -> overview
    Route::get('/teacher', fn () => redirect()->route('web.teacher.overview'))->name('web.teacher.home');

    Route::post('/teacher/requests/{assignmentSupervisor}/accept', [AssignmentSupervisorController::class, 'updateStatus'])
        ->name('web.teacher.requests.accept');

    Route::post('/teacher/requests/{assignmentSupervisor}/reject', [AssignmentSupervisorController::class, 'updateStatus'])
        ->name('web.teacher.requests.reject');

    //stage 2
    Route::get('/teacher/supervised-outline-reports/{supervisorId}/term/{termId}', [WebProjectTermsController::class, 'getDetailProjectTermBySupervisorId'])->name('web.teacher.supervised_outline_reports');
    Route::get('/teacher/outline-review-assignments/term/{termId}/supervisor/{supervisorId}', [WebAssignmentController::class, 'outlineReviewAssignments'])->name('web.teacher.outline_review_assignments');
    Route::get('/teacher/supervised-student-detail/{studentId}/term/{termId}/supervisor/{supervisorId}', [WebAssignmentController::class, 'getAssignmentByStudentIdAndTermId'])->name('web.teacher.supervised_student_detail');

    Route::patch('/report-files/{report_file}/status', [WebReportFilesController::class, 'update'])
        ->name('web.teacher.report_files.update_status');
    Route::patch('/attachments/{progress_log}/status', [WebAttachmentController::class, 'updateStatus'])
        ->name('web.teacher.attachments.update_status');
    //stage 5

    Route::get('/teacher/my-committees/supervisor/{supervisorId}/term/{termId}', [WebCouncilMembersController::class, 'getCouncilMembersBySupervisorIdandTermId'])->name('web.teacher.my_committees');
    Route::get('/teacher/student-committee/supervisor/{supervisorId}/term/{termId}', [WebProjectTermsController::class, 'studentCommitee'])->name('web.teacher.student_committee');
    Route::get('/teacher/committee-detail/{councilId}/term/{termId}/supervisor/{supervisorId}', [WebCouncilController::class, 'getCouncilDetail' ])->name('web.teacher.committee_detail');
    Route::post('/teacher/councils/{council}/assign-reviewer', [CouncilProjectsController::class, 'assign'])
        ->name('web.teacher.councils.assign_reviewer');

    //stage 6
    Route::get('/teacher/review-assignments/supervisor/{supervisorId}/council/{councilId}/term/{termId}', [WebProjectTermsController::class, 'reviewAssignment'])->name('web.teacher.review_assignments');
    Route::get('/teacher/review-council/supervisor/{supervisorId}/term/{termId}', [WebCouncilMembersController::class, 'reviewCouncil'])->name('web.teacher.review_council');
    Route::get('/teacher/student-review/term/{termId}/supervisor/{supervisorId}', [WebProjectTermsController::class, 'studentReviews'])->name('web.teacher.student_review');


    //stage 8 
    Route::get('/teacher/my-council-scoring/supervisor/{supervisorId}/term/{termId}', [WebCouncilMembersController::class, 'myCouncilScoring'])->name('web.teacher.my_evaluations');
    Route::get('/teacher/council-scoring-detail/supervisor/{supervisorId}/council/{councilId}/term/{termId}', [WebProjectTermsController::class, 'scoringCouncilDetail' ])->name('web.teacher.council_scoring_detail');
    Route::get('/council-projects/{council_project}', [WebCouncilProjectController::class, 'show'])
            ->name('web.teacher.council_projects.show');
    Route::get('/teacher/student-council/term/{termId}/supervisor/{supervisorId}', [WebProjectTermsController::class, 'studentCouncil'])->name('web.teacher.student_council');
    Route::post('/council-projects/{council_project}/defences', [WebCouncilProjectDefencesController::class, 'store'])
            ->name('web.teacher.councile_project_defences.store');
});

Route::middleware(['web','auth'])->group(function () {
    // Trang phân công GVHD cho 1 đợt
    Route::get('/assistant/rounds/{termId}/assign-supervisors', function ($termId) {
        return view('assistant-ui.assign-supervisors', compact('termId'));
    })->name('web.assistant.assign_supervisors');
});

Route::middleware(['web','auth'])->prefix('teacher')->name('web.teacher.')->group(function () {
    // Cập nhật trạng thái phản biện (duyệt/từ chối/...)
    Route::post('/assignments/{assignment}/counter-status/{reportFile}', [WebAssignmentController::class, 'setCounterStatus'])
        ->name('assignments.counter_status');
    Route::post('/report-files/{reportFile}/status', [ReportFilesController::class, 'setStatus'])
        ->name('report_files.set_status');
    // Lưu điểm phản biện cho 1 council_project
    Route::post('/reviews/{council_project}', [WebCouncilProjectController::class, 'update_review_score'])
        ->name('reviews.store');
    Route::post('/assignment-supervisors/{assignmentSupervisor}/report-score', [AssignmentSupervisorController::class, 'updateReportScore'])
        ->name('assignment_supervisors.report_score');
});

Route::middleware(['auth','verified'])->prefix('assistant')->name('web.assistant.')->group(function () {
    // Tạo bộ môn (đã có)
    Route::post('/departments', [DepartmentController::class, 'store'])->name('departments.store');
    // Cập nhật bộ môn
    Route::patch('/departments/{id}', [DepartmentController::class, 'update'])->name('departments.update');
    Route::delete('/departmentRole/{id}', [WebDepartmentRolesController::class, 'delete'])->name('departmentRole.destroy');
    Route::delete('/departments/{id}', [WebDepartmentController::class, 'destroy'])->name('departments.destroy');
    Route::post('/department-roles', [WebDepartmentRolesController::class, 'assignHead'])
        ->name('department_roles.store');
    Route::post('/majors', [MarjorController::class, 'store'])->name('majors.store');
    Route::patch('/majors/{id}', [MarjorController::class, 'update'])->name('majors.update');
    Route::delete('/majors/{id}', [MarjorController::class, 'delete'])->name('majors.destroy');
    Route::get('/rounds/{termId}/deferments', function ($termId) {
        return view('assistant-ui.deferments', ['termId' => $termId]);
    })->name('deferred_students');
    Route::patch('deferments.update', [WebAssignmentController::class, 'updateDeferment'])->name('deferments.update');
});
