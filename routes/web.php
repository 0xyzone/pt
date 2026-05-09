<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});
Route::prefix('{user_id}/screens')->group( function () {
    Route::get('/postmatch', [\App\Http\Controllers\ScreenController::class, 'postmatch'])->name('screens.postmatch');
});
Route::passkeys();