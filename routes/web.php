<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\VisitorController;

Route::get('/', function () {
    return view('welcome');
    // return 'Hello, World!';
});


Route::get('/fcm-test', function () {
    return view('fcm-test');
});

// Route::get('/test-firebase', [VisitorController::class, 'testFirebaseNotification']);

// Route::get('/fcm-keypair', function () {
//     return view('fcm-keypair');
// });
