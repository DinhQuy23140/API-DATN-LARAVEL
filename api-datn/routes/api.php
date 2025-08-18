<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AttachmentController;
use App\Http\Controllers\ProgressLogController;


Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::apiResource('progress-logs', ProgressLogController::class);

Route::apiResource('progress-logs.attachments', AttachmentController::class)->shallow();

Route::get('/all-attachments', [AttachmentController::class, 'getAllAttachment']);

