<?php

use App\Http\Controllers\ScreenController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});
Route::prefix('{user_id}/screens')->group( function () {
    Route::get('/activematch', [ScreenController::class, 'activematch'])->name('screens.activematch');
    Route::get('/postmatch', [ScreenController::class, 'postMatch'])->name('screens.postmatch');
});
Route::passkeys();