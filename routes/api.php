<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\EmployeeController;
use App\Http\Controllers\Api\VisitorController;
use Illuminate\Support\Facades\Mail;


Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login'])->name('login');

Route::get('/test-mail', function () {
    Mail::raw('SMTP mail test from Laravel 12', function ($message) {
        $message->to('rahul.chugh@arkinfo.in')
                ->subject('SMTP Test');
    });

    return 'Mail sent';
});

Route::get('/test', function () {
    return 'Mail sent';
});

Route::middleware('auth:sanctum')->group(function () {
Route::post('/logout', [AuthController::class, 'logout']);
Route::get('/employees', [EmployeeController::class, 'index']);
Route::post('/visitors', [VisitorController::class, 'store']);
Route::get('/visitors', [VisitorController::class, 'index']);
Route::get('/visitors-list', [VisitorController::class, 'visitorsList']);
Route::get('/view-visitor/{id}', [VisitorController::class, 'show']); // View visitor detail
Route::post('/visitors/{id}/approve', [VisitorController::class, 'approveVisitor']);
Route::post('/visitors/card-number', [VisitorController::class, 'updateCardNumber']);
Route::post('/visitors/{id}/exit', [VisitorController::class, 'updateExitStatus']);
Route::post('/visitors/{id}/action', [VisitorController::class, 'updateVisitorAction']);
Route::post('/manage-visit-status', [VisitorController::class, 'manageVisitStatus']);
Route::post('/manage-exit-status', [VisitorController::class, 'manageExitStatus']);
});