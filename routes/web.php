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
    Route::get('/mappool', [ScreenController::class, 'mapPool'])->name('screens.mappool');
    Route::get('/pointsystem', [ScreenController::class, 'pointSystem'])->name('screens.pointsystem');
    Route::get('/castersscreen', [ScreenController::class, 'castersScreen'])->name('screens.castersscreen');
    Route::get('/headtohead', [ScreenController::class, 'headToHead'])->name('screens.headtohead');
    Route::get('/topfraggers', [ScreenController::class, 'topFraggers'])->name('screens.topfraggers');
    Route::get('/roadmap', [ScreenController::class, 'roadmap'])->name('screens.roadmap');
    Route::post('/update-h2h', [ScreenController::class, 'updateH2H'])->name('screens.updateh2h');
    
    // OBS Overlays and Control Panel
    Route::get('/obs-master', [ScreenController::class, 'obsMaster'])->name('screens.obsmaster');
    Route::get('/control-panel', [ScreenController::class, 'controlPanel'])->name('screens.controlpanel');
    Route::post('/switch-view', [ScreenController::class, 'switchObsView'])->name('screens.switchview');
    Route::post('/toggle-visibility', [ScreenController::class, 'toggleActiveMatchVisibility'])->name('screens.togglevisibility');
    Route::post('/refresh-screens', [ScreenController::class, 'refreshScreens'])->name('screens.refresh');
    Route::get('/stats-control', [ScreenController::class, 'statsControl'])->name('screens.statscontrol');
    Route::post('/update-stat', [ScreenController::class, 'updateMatchStat'])->name('screens.updatestat');
    Route::post('/complete-match', [ScreenController::class, 'completeActiveMatch'])->name('screens.completematch');
    Route::post('/incomplete-match', [ScreenController::class, 'makeActiveMatchIncomplete'])->name('screens.incompletematch');
    Route::get('/slot-list', [ScreenController::class, 'slotList'])->name('screens.slotlist');
    Route::get('/startingsoon', [ScreenController::class, 'startingSoon'])->name('screens.startingsoon');
    Route::get('/ending', [ScreenController::class, 'endingScreen'])->name('screens.ending');
    Route::post('/update-timer', [ScreenController::class, 'updateTimer'])->name('screens.updatetimer');
    Route::post('/update-bg', [ScreenController::class, 'updateBackground'])->name('screens.updatebg');
    Route::post('/upload-video', [ScreenController::class, 'uploadVideo'])->name('screens.uploadvideo');
    Route::post('/delete-video', [ScreenController::class, 'deleteVideo'])->name('screens.deletevideo');
});
Route::passkeys();