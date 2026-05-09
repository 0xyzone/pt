<?php

use App\Http\Controllers\ApiController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// Route::get('/user', function (Request $request) {
//     return $request->user();
// })->middleware('auth:sanctum');

Route::prefix('{user_id}')->group( function () {
    Route::get('/', [ApiController::class, 'getAll']);
    Route::get('/activeMatch', [ApiController::class, 'activeMatch']);
});
