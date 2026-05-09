<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});
Route::prefix('{user_id}/screens')->group( function () {
    Route::get('/activematch', [\App\Http\Controllers\ScreenController::class, 'activematch'])->name('screens.activematch');
});
Route::passkeys();