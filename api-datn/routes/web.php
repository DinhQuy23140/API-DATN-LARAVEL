<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Web\UserController as WebUserController;
use App\Http\Controllers\Web\ProgressLogController as WebProgressLogController;
use App\Http\Controllers\Web\AttachmentController as WebAttachmentController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::prefix('users')->name('web.users.')->group(function () {
    Route::get('/', [WebUserController::class, 'index'])->name('index');
    Route::get('/create', [WebUserController::class, 'create'])->name('create');
    Route::post('/', [WebUserController::class, 'store'])->name('store');
    Route::get('/{user}', [WebUserController::class, 'show'])->name('show');
    Route::get('/{user}/edit', [WebUserController::class, 'edit'])->name('edit');
    Route::put('/{user}', [WebUserController::class, 'update'])->name('update');
    Route::delete('/{user}', [WebUserController::class, 'destroy'])->name('destroy');
});

Route::prefix('progress-logs')->name('web.progress_logs.')->group(function () {
    Route::get('/', [WebProgressLogController::class, 'index'])->name('index');
    Route::get('/create', [WebProgressLogController::class, 'create'])->name('create');
    Route::post('/', [WebProgressLogController::class, 'store'])->name('store');
    Route::get('/{progress_log}', [WebProgressLogController::class, 'show'])->name('show');
    Route::get('/{progress_log}/edit', [WebProgressLogController::class, 'edit'])->name('edit');
    Route::put('/{progress_log}', [WebProgressLogController::class, 'update'])->name('update');
    Route::delete('/{progress_log}', [WebProgressLogController::class, 'destroy'])->name('destroy');

    // Nested attachments (only create/store) referencing parent log
    Route::get('/{progress_log}/attachments/create', [WebAttachmentController::class, 'create'])->name('attachments.create');
    Route::post('/{progress_log}/attachments', [WebAttachmentController::class, 'store'])->name('attachments.store');
});

// Attachment routes that operate on single attachment (edit/update/delete)
Route::get('attachments/{attachment}/edit', [WebAttachmentController::class, 'edit'])->name('web.attachments.edit');
Route::put('attachments/{attachment}', [WebAttachmentController::class, 'update'])->name('web.attachments.update');
Route::delete('attachments/{attachment}', [WebAttachmentController::class, 'destroy'])->name('web.attachments.destroy');
