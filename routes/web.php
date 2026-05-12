<?php

use App\Http\Controllers\ScreenController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});
Route::prefix('{user_id}/screens')->group( function () {
    Route::get('/activematch', [ScreenController::class, 'activematch'])->name('screens.activematch');
    Route::get('/postmatch', [ScreenController::class, 'postMatch'])->name('screens.postmatch');
    Route::get('/overallranking', [ScreenController::class, 'overallRanking'])->name('screens.overallranking');
    Route::get('/teamelimination', [ScreenController::class, 'teamElimination'])->name('screens.teamelimination');
    Route::get('/upcomingmatches', [ScreenController::class, 'upcomingMatches'])->name('screens.upcomingmatches');
    Route::get('/mapscreen', [ScreenController::class, 'mapScreen'])->name('screens.mapscreen');
    
    // OBS Overlays and Control Panel
    Route::get('/obs-master', [ScreenController::class, 'obsMaster'])->name('screens.obsmaster');
    Route::get('/control-panel', [ScreenController::class, 'controlPanel'])->name('screens.controlpanel');
    Route::post('/switch-view', [ScreenController::class, 'switchObsView'])->name('screens.switchview');
    Route::post('/toggle-visibility', [ScreenController::class, 'toggleActiveMatchVisibility'])->name('screens.togglevisibility');
    Route::get('/stats-control', [ScreenController::class, 'statsControl'])->name('screens.statscontrol');
    Route::post('/update-stat', [ScreenController::class, 'updateMatchStat'])->name('screens.updatestat');
    Route::get('/slot-list', [ScreenController::class, 'slotList'])->name('screens.slotlist');
});
Route::passkeys();