<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Cache;

class ScreenController extends Controller
{
    public function activematch(Request $request)
    {
        $user = User::find($request->route('user_id'));
        $activeMatch = $user->getActiveMatch();
        return view('screens.activeMatch', compact('activeMatch'));
    }
    
    public function postMatch(Request $request)
    {
        $user = User::find($request->route('user_id'));
        $activeMatch = $user->getActiveMatch();

        $bgTypeKey = "bg_type_{$user->id}";
        $bgType = Cache::get($bgTypeKey, 'transparent');
        $customVideoKey = "custom_video_{$user->id}";
        $customVideo = Cache::get($customVideoKey);

        return view('screens.postMatch', compact('activeMatch', 'bgType', 'customVideo'));
    }

    public function overallRanking(Request $request)
    {
        $user = User::find($request->route('user_id'));
        $activeMatch = $user->getActiveMatch();
        $tournament = $activeMatch->tournament;

        // Scope to the current round if the active match belongs to one,
        // otherwise fall back to all tournament matches.
        $currentRound = $activeMatch->tournamentRound;
        if ($currentRound) {
            $matches = $currentRound->tournamentMatches()->with('matchStats.tournamentTeam')->get();
        } else {
            $matches = $tournament->tournamentMatches()->with('matchStats.tournamentTeam')->get();
        }

        $teamsData = collect();

        foreach ($matches as $match) {
            foreach ($match->matchStats as $stat) {
                if (!$teamsData->has($stat->tournament_team_id)) {
                    $teamsData->put($stat->tournament_team_id, [
                        'team' => $stat->tournamentTeam,
                        'total_points' => 0,
                        'total_kills' => 0,
                        'total_placement_points' => 0,
                        'matches_played' => 0,
                        'total_wins' => 0,
                    ]);
                }

                $data = $teamsData->get($stat->tournament_team_id);
                $data['total_points'] += $stat->points;
                $data['total_kills'] += $stat->kills;
                $data['total_placement_points'] += ($stat->points - $stat->kills);

                if ($match->is_completed) {
                    $data['matches_played'] += 1;
                }

                if ($stat->placement == 1) {
                    $data['total_wins'] += 1;
                }

                $teamsData->put($stat->tournament_team_id, $data);
            }
        }

        // Sort primarily by points, then by kills
        $rankings = $teamsData->sortBy([
            ['total_points', 'desc'],
            ['total_kills', 'desc'],
        ])->values();

        $bgTypeKey = "bg_type_{$user->id}";
        $bgType = Cache::get($bgTypeKey, 'transparent');
        $customVideoKey = "custom_video_{$user->id}";
        $customVideo = Cache::get($customVideoKey);

        return view('screens.overallRanking', compact('tournament', 'rankings', 'activeMatch', 'currentRound', 'bgType', 'customVideo'));
    }

    public function teamElimination(Request $request)
    {
        $user = User::findOrFail($request->route('user_id'));
        $activeMatch = $user->getActiveMatch();
        return view('screens.teamElimination', compact('activeMatch', 'user'));
    }

    public function upcomingMatches(Request $request)
    {
        $user = User::findOrFail($request->route('user_id'));
        $activeMatch = $user->getActiveMatch();
        $tournament = $activeMatch->tournament;
        $sponsors = $tournament->tournamentSponsors;
        
        $currentRound = $activeMatch->tournamentRound;
        if ($currentRound) {
            $matches = $currentRound->tournamentMatches()->with('matchStats.tournamentTeam')->orderBy('id')->get();
        } else {
            $matches = $tournament->tournamentMatches()->with('matchStats.tournamentTeam')->orderBy('match_date')->orderBy('match_time')->get();
        }

        $durationKey = "timer_duration_{$user->id}";
        $statusKey = "timer_status_{$user->id}";
        $endsAtKey = "timer_ends_at_{$user->id}";
        $remainingKey = "timer_remaining_{$user->id}";
        $visibleKey = "timer_visible_{$user->id}";
        $showHoursKey = "timer_show_hours_{$user->id}";

        $timerState = [
            'duration' => (int)Cache::get($durationKey, 600),
            'status' => Cache::get($statusKey, 'stopped'),
            'endsAt' => (int)Cache::get($endsAtKey, 0),
            'remainingSeconds' => (int)Cache::get($remainingKey, 600),
            'visible' => (bool)Cache::get($visibleKey, true),
            'showHours' => (bool)Cache::get($showHoursKey, false),
        ];

        return view('screens.upcomingMatches', compact('activeMatch', 'user', 'tournament', 'matches', 'sponsors', 'timerState'));
    }

    public function mapScreen(Request $request)
    {
        $user = User::findOrFail($request->route('user_id'));
        $activeMatch = $user->getActiveMatch();
        $tournament = $activeMatch->tournament;
        
        // Retrieve all teams for the active match via matchStats, or tournament teams if match hasn't started stats yet.
        // Usually, teams are added to matchStats when match is active.
        $teams = $activeMatch->matchStats()->with('tournamentTeam')->get()->map->tournamentTeam;
        if ($teams->isEmpty()) {
            $teams = $tournament->tournamentTeams;
        }
        
        return view('screens.mapScreen', compact('activeMatch', 'user', 'tournament', 'teams'));
    }

    public function castersScreen(Request $request)
    {
        $user = User::findOrFail($request->route('user_id'));
        $activeMatch = $user->getActiveMatch();
        $tournament = $activeMatch ? $activeMatch->tournament : null;
        
        // Load the casters associated with this tournament
        $casters = $tournament ? $tournament->casters()->get() : collect();

        $bgTypeKey = "bg_type_{$user->id}";
        $bgType = Cache::get($bgTypeKey, 'transparent');
        $customVideoKey = "custom_video_{$user->id}";
        $customVideo = Cache::get($customVideoKey);

        return view('screens.castersScreen', compact('user', 'tournament', 'casters', 'activeMatch', 'bgType', 'customVideo'));
    }

    public function obsMaster(Request $request)
    {
        $user = User::findOrFail($request->route('user_id'));
        $activeMatch = $user->getActiveMatch();
        return view('screens.obsMaster', compact('activeMatch', 'user'));
    }

    public function controlPanel(Request $request)
    {
        $user = User::findOrFail($request->route('user_id'));

        $durationKey = "timer_duration_{$user->id}";
        $statusKey = "timer_status_{$user->id}";
        $endsAtKey = "timer_ends_at_{$user->id}";
        $remainingKey = "timer_remaining_{$user->id}";
        $visibleKey = "timer_visible_{$user->id}";
        $showHoursKey = "timer_show_hours_{$user->id}";

        $timerState = [
            'duration' => (int)Cache::get($durationKey, 600),
            'status' => Cache::get($statusKey, 'stopped'),
            'endsAt' => (int)Cache::get($endsAtKey, 0),
            'remainingSeconds' => (int)Cache::get($remainingKey, 600),
            'visible' => (bool)Cache::get($visibleKey, true),
            'showHours' => (bool)Cache::get($showHoursKey, false),
        ];

        $bgTypeKey = "bg_type_{$user->id}";
        $bgType = Cache::get($bgTypeKey, 'transparent');
        $customVideoKey = "custom_video_{$user->id}";
        $customVideo = Cache::get($customVideoKey);

        $activeMatch = $user->getActiveMatch();
        $teams = collect();
        if ($activeMatch) {
            $teams = $activeMatch->tournament->tournamentTeams()->orderBy('name')->get();
        }

        $tournamentSetting = $activeMatch?->tournament?->tournamentSettings()->first();
        $obsPassword = $tournamentSetting?->obs_password;

        return view('screens.controlPanel', compact('user', 'timerState', 'bgType', 'customVideo', 'activeMatch', 'teams', 'obsPassword'));
    }

    public function statsControl(Request $request)
    {
        $user = User::findOrFail($request->route('user_id'));
        $activeMatch = $user->getActiveMatch();
        
        if (!$activeMatch) {
            return redirect()->route('screens.controlpanel', ['user_id' => $user->id])
                ->with('status', 'No active match found. Please activate a match first.');
        }

        $activeMatch->load(['matchStats.tournamentTeam', 'matchStats.players', 'tournament.tournamentSettings.tournamentSettingPlacementPoints']);
        $tournamentSetting = $activeMatch->tournament->tournamentSettings->first();
        $placementOptions = $tournamentSetting
            ? $tournamentSetting->tournamentSettingPlacementPoints->pluck('placement')->toArray()
            : [];
            
        // Map team IDs to their slot numbers (index + 2) based on ID order
        $orderedTeams = $activeMatch->tournament->tournamentTeams()->orderBy('id')->get();
        $teamSlots = $orderedTeams->pluck('id')->flip()->map(fn($i) => $i + 2);

        $obsPassword = $tournamentSetting?->obs_password;

        return view('screens.statsControl', compact('user', 'activeMatch', 'placementOptions', 'teamSlots', 'obsPassword'));
    }

    public function updateMatchStat(Request $request)
    {
        $statId   = (int) $request->input('stat_id');
        $playerId = $request->input('player_id') ? (int) $request->input('player_id') : null;
        $field    = $request->input('field');

        // ----------------------------------------------------------------
        // Strict value casting — prevents "0" string truthy-ness in PHP
        // and ensures the JSON response always contains real primitives.
        // ----------------------------------------------------------------
        $rawValue = $request->input('value');
        $value    = match ($field) {
            'is_winner', 'is_alive' => filter_var($rawValue, FILTER_VALIDATE_BOOLEAN),
            'kills', 'placement', 'alive' => (int) $rawValue,
            default => $rawValue,
        };

        $stat = \App\Models\MatchStat::findOrFail($statId);
        $stat->load(['tournamentMatch.tournament.tournamentSettings.tournamentSettingPlacementPoints', 'tournamentTeam', 'players']);

        if ($playerId) {
            // ── Player-level update ──────────────────────────────────────
            $allowed = ['is_alive', 'kills'];
            if (!in_array($field, $allowed)) {
                return response()->json(['error' => 'Invalid player field'], 422);
            }

            $wasAlive = $stat->alive > 0;

            // Cast is_alive to integer for the pivot (MySQL TINYINT)
            $pivotValue = ($field === 'is_alive') ? (int) $value : $value;
            $stat->players()->updateExistingPivot($playerId, [$field => $pivotValue]);
            $stat->recalculateTotals();

            // Fire elimination event if team alive count just transitioned from >0 to 0
            if ($wasAlive && $stat->alive === 0) {
                event(new \App\Events\TeamEliminated(
                    $stat->tournamentTeam->name,
                    $stat->tournamentTeam->logo_image,
                    $stat->tournament_match_id
                ));
            }

            $stat->refresh();
            return response()->json([
                'success' => true,
                'stat'    => $stat->load(['tournamentTeam', 'players']),
            ]);
        }

        // ── Team-level update ────────────────────────────────────────────
        $allowed = ['placement', 'is_winner', 'alive'];
        if (!in_array($field, $allowed)) {
            return response()->json(['error' => 'Invalid team field'], 422);
        }

        if ($field === 'alive') {
            $wasAlive = $stat->alive > 0;
            foreach ($stat->players as $player) {
                $stat->players()->updateExistingPivot($player->id, ['is_alive' => $value ? 1 : 0]);
            }
            $stat->recalculateTotals();

            if ($wasAlive && $stat->alive === 0) {
                event(new \App\Events\TeamEliminated(
                    $stat->tournamentTeam->name,
                    $stat->tournamentTeam->logo_image,
                    $stat->tournament_match_id
                ));
            }

            $stat->refresh();
            return response()->json([
                'success' => true,
                'stat'    => $stat->load(['tournamentTeam', 'players']),
            ]);
        }

        // Handle winner toggle — clear other winners in the same match first
        if ($field === 'is_winner' && $value === true) {
            \App\Models\MatchStat::where('tournament_match_id', $stat->tournament_match_id)
                ->where('id', '!=', $stat->id)
                ->update(['is_winner' => false]);

            $stat->update([
                'is_winner' => true,
                'placement' => 1,
            ]);
        } else {
            $stat->update([$field => $value]);
        }

        // Recalculate points when placement or winner changes
        if ($field === 'placement' || ($field === 'is_winner' && $value === true)) {
            $stat->refresh();
            $tournamentSetting = $stat->tournamentMatch?->tournament?->tournamentSettings->first();
            $points = 0;
            if ($tournamentSetting) {
                $killPoints      = ($tournamentSetting->kill_points ?? 0) * $stat->kills;
                $placementPoints = $tournamentSetting->tournamentSettingPlacementPoints
                    ->where('placement', $stat->placement)
                    ->first()?->points ?? 0;
                $points = $killPoints + $placementPoints;
            }
            $stat->update(['points' => $points]);
        }

        $stat->refresh();
        return response()->json([
            'success' => true,
            'stat'    => $stat->load(['tournamentTeam', 'players']),
        ]);
    }

    public function switchObsView(Request $request)
    {
        $userId = $request->route('user_id');
        $viewName = $request->input('view'); // 'empty', 'postmatch', 'overallranking'
        $this->safeBroadcast(new \App\Events\ObsViewSwitched($userId, $viewName));
        return back()->with('status', 'View switched to ' . $viewName);
    }

    public function toggleActiveMatchVisibility(Request $request)
    {
        $userId = $request->route('user_id');
        $isVisible = $request->input('visible') == '1';
        $this->safeBroadcast(new \App\Events\ActiveMatchVisibilityToggled($userId, $isVisible));
        return back()->with('status', 'Active Match visibility toggled to ' . ($isVisible ? 'Visible' : 'Hidden'));
    }

    public function refreshScreens(Request $request)
    {
        $userId = $request->route('user_id');
        $this->safeBroadcast(new \App\Events\RefreshScreensEvent($userId));
        return back()->with('status', 'Refresh signal sent to all screens.');
    }

    public function slotList(Request $request)
    {
        $user = User::findOrFail($request->route('user_id'));
        $activeMatch = $user->getActiveMatch();
        
        $tournament = null;
        if ($activeMatch) {
            $tournament = $activeMatch->tournament;
        } else {
            // Fallback to latest tournament if no active match
            $tournament = \App\Models\Tournament::where('user_id', $user->id)->latest()->first();
        }

        $teams = collect();
        if ($tournament) {
            $teams = $tournament->tournamentTeams()->orderBy('id')->get();
        }

        return view('screens.slotList', compact('tournament', 'teams'));
    }

    public function startingSoon(Request $request)
    {
        $user = User::findOrFail($request->route('user_id'));
        $activeMatch = $user->getActiveMatch();
        $tournament = $activeMatch->tournament;
        $sponsors = $tournament->tournamentSponsors;

        $durationKey = "timer_duration_{$user->id}";
        $statusKey = "timer_status_{$user->id}";
        $endsAtKey = "timer_ends_at_{$user->id}";
        $remainingKey = "timer_remaining_{$user->id}";
        $visibleKey = "timer_visible_{$user->id}";
        $showHoursKey = "timer_show_hours_{$user->id}";

        $timerState = [
            'duration' => (int)Cache::get($durationKey, 600),
            'status' => Cache::get($statusKey, 'stopped'),
            'endsAt' => (int)Cache::get($endsAtKey, 0),
            'remainingSeconds' => (int)Cache::get($remainingKey, 600),
            'visible' => (bool)Cache::get($visibleKey, true),
            'showHours' => (bool)Cache::get($showHoursKey, false),
        ];

        $bgTypeKey = "bg_type_{$user->id}";
        $bgType = Cache::get($bgTypeKey, 'transparent');
        $customVideoKey = "custom_video_{$user->id}";
        $customVideo = Cache::get($customVideoKey);

        return view('screens.startingSoon', compact('activeMatch', 'user', 'tournament', 'sponsors', 'timerState', 'bgType', 'customVideo'));
    }

    public function endingScreen(Request $request)
    {
        $user = User::findOrFail($request->route('user_id'));
        $activeMatch = $user->getActiveMatch();
        $tournament = $activeMatch->tournament;
        $sponsors = $tournament->tournamentSponsors;

        $durationKey = "timer_duration_{$user->id}";
        $statusKey = "timer_status_{$user->id}";
        $endsAtKey = "timer_ends_at_{$user->id}";
        $remainingKey = "timer_remaining_{$user->id}";
        $visibleKey = "timer_visible_{$user->id}";
        $showHoursKey = "timer_show_hours_{$user->id}";

        $timerState = [
            'duration' => (int)Cache::get($durationKey, 600),
            'status' => Cache::get($statusKey, 'stopped'),
            'endsAt' => (int)Cache::get($endsAtKey, 0),
            'remainingSeconds' => (int)Cache::get($remainingKey, 600),
            'visible' => (bool)Cache::get($visibleKey, true),
            'showHours' => (bool)Cache::get($showHoursKey, false),
        ];

        $bgTypeKey = "bg_type_{$user->id}";
        $bgType = Cache::get($bgTypeKey, 'transparent');
        $customVideoKey = "custom_video_{$user->id}";
        $customVideo = Cache::get($customVideoKey);

        return view('screens.endingScreen', compact('activeMatch', 'user', 'tournament', 'sponsors', 'timerState', 'bgType', 'customVideo'));
    }

    public function updateTimer(Request $request)
    {
        $userId = $request->route('user_id');
        $action = $request->input('action');
        
        $durationKey   = "timer_duration_{$userId}";    // total seconds
        $statusKey     = "timer_status_{$userId}";
        $endsAtKey     = "timer_ends_at_{$userId}";
        $remainingKey  = "timer_remaining_{$userId}";
        $visibleKey    = "timer_visible_{$userId}";
        $showHoursKey  = "timer_show_hours_{$userId}";
        
        // Defaults (duration now stores total seconds)
        $duration  = (int)Cache::get($durationKey, 600);  // seconds
        $status    = Cache::get($statusKey, 'stopped');
        $endsAt    = (int)Cache::get($endsAtKey, 0);
        $remaining = (int)Cache::get($remainingKey, $duration);
        $visible   = (bool)Cache::get($visibleKey, true);
        $showHours = (bool)Cache::get($showHoursKey, false);
        
        if ($action === 'set-duration') {
            // Accept hours / minutes / seconds as separate inputs
            $hours   = max(0, (int)$request->input('hours', 0));
            $minutes = max(0, (int)$request->input('minutes', 10));
            $seconds = max(0, (int)$request->input('seconds', 0));
            
            $totalSeconds = ($hours * 3600) + ($minutes * 60) + $seconds;
            if ($totalSeconds < 1) $totalSeconds = 60; // minimum 1 second
            
            $showHours = $hours > 0;
            
            Cache::put($durationKey, $totalSeconds, 86400);
            Cache::put($showHoursKey, $showHours, 86400);
            $duration = $totalSeconds;
            
            // If stopped, reset remaining seconds to new total
            if ($status === 'stopped') {
                $remaining = $totalSeconds;
                Cache::put($remainingKey, $remaining, 86400);
            } else if ($status === 'running') {
                // Adjust active running timer endpoint
                $endsAt = time() + $totalSeconds;
                Cache::put($endsAtKey, $endsAt, 86400);
            }
        } elseif ($action === 'start') {
            if ($status !== 'running') {
                $status = 'running';
                $endsAt = time() + $remaining;
                Cache::put($statusKey, $status, 86400);
                Cache::put($endsAtKey, $endsAt, 86400);
            }
        } elseif ($action === 'pause') {
            if ($status === 'running') {
                $status = 'paused';
                $remaining = max(0, $endsAt - time());
                Cache::put($statusKey, $status, 86400);
                Cache::put($remainingKey, $remaining, 86400);
                Cache::put($endsAtKey, 0, 86400);
            }
        } elseif ($action === 'reset') {
            $status    = 'stopped';
            $remaining = $duration;   // duration is already total seconds
            $endsAt    = 0;
            Cache::put($statusKey, $status, 86400);
            Cache::put($remainingKey, $remaining, 86400);
            Cache::put($endsAtKey, $endsAt, 86400);
        } elseif ($action === 'toggle-visibility') {
            $visible = $request->input('visible') == '1';
            Cache::put($visibleKey, $visible, 86400);
        }
        
        // Broadcast the real-time event to all overlays
        $this->safeBroadcast(new \App\Events\TimerUpdated($userId, $status, $duration, $remaining, $endsAt, $visible, $showHours));
        
        return response()->json([
            'success'        => true,
            'status'         => $status,
            'duration'       => $duration,
            'remainingSeconds' => $remaining,
            'endsAt'         => $endsAt,
            'visible'        => $visible,
            'showHours'      => $showHours,
        ]);
    }

    public function updateBackground(Request $request)
    {
        $userId = $request->route('user_id');
        $bgType = $request->input('bg_type', 'transparent'); // 'transparent', 'animated', or 'custom'
        
        $bgTypeKey = "bg_type_{$userId}";
        Cache::put($bgTypeKey, $bgType, 86400);

        // Resolve custom video URL if switching to custom
        $customVideoUrl = null;
        $customVideoKey = "custom_video_{$userId}";
        $customVideoPath = Cache::get($customVideoKey);
        if ($customVideoPath) {
            $customVideoUrl = asset('storage/' . $customVideoPath);
        }
        
        // Broadcast targeted background-change event with full payload
        $this->safeBroadcast(new \App\Events\BackgroundChanged($userId, $bgType, $customVideoUrl));
        
        return response()->json([
            'success' => true,
            'bg_type' => $bgType,
            'custom_video_url' => $customVideoUrl,
        ]);
    }

    public function uploadVideo(Request $request)
    {
        $user = User::findOrFail($request->route('user_id'));
        
        $request->validate([
            'video' => 'required|mimes:mp4,webm,mov,ogg|max:51200', // 50MB
        ]);

        if ($request->hasFile('video')) {
            $file = $request->file('video');
            $extension = $file->getClientOriginalExtension();
            $filename = 'custom_bg_video_' . $user->id . '_' . time() . '.' . $extension;

            // Delete old file if exists
            $customVideoKey = "custom_video_{$user->id}";
            $oldPath = Cache::get($customVideoKey);
            if ($oldPath && Storage::disk('public')->exists($oldPath)) {
                Storage::disk('public')->delete($oldPath);
            }

            // Store new file
            $path = $file->storeAs('videos', $filename, 'public');

            // Save new path in cache
            Cache::put($customVideoKey, $path, 86400 * 365); // persistent cache

            // Update bg type to custom
            $bgTypeKey = "bg_type_{$user->id}";
            Cache::put($bgTypeKey, 'custom', 86400);

            // Broadcast refresh screens event so overlay shifts in real time
            $this->safeBroadcast(new \App\Events\RefreshScreensEvent($user->id));

            return back()->with('status', 'Custom background video uploaded successfully.');
        }

        return back()->withErrors(['video' => 'Failed to upload video file.']);
    }

    public function deleteVideo(Request $request)
    {
        $user = User::findOrFail($request->route('user_id'));

        $customVideoKey = "custom_video_{$user->id}";
        $oldPath = Cache::get($customVideoKey);

        // Delete from local storage
        if ($oldPath && Storage::disk('public')->exists($oldPath)) {
            Storage::disk('public')->delete($oldPath);
        }

        // Clean up cache
        Cache::forget($customVideoKey);

        // Reset background type to transparent (default)
        $bgTypeKey = "bg_type_{$user->id}";
        Cache::put($bgTypeKey, 'transparent', 86400);

        // Broadcast refresh screens event
        $this->safeBroadcast(new \App\Events\RefreshScreensEvent($user->id));

        return back()->with('status', 'Custom background video deleted successfully and reset to transparent background.');
    }

    public function updateH2H(Request $request)
    {
        $userId = $request->route('user_id');
        $team1 = $request->input('team1');
        $team2 = $request->input('team2');
        
        Cache::put("h2h_team_1_{$userId}", $team1, 86400);
        Cache::put("h2h_team_2_{$userId}", $team2, 86400);
        
        $this->safeBroadcast(new \App\Events\ObsViewSwitched($userId, 'refresh'));
        
        return back()->with('status', 'Head-to-head comparison matchup updated successfully.');
    }

    public function headToHead(Request $request)
    {
        $user = User::findOrFail($request->route('user_id'));
        $activeMatch = $user->getActiveMatch();
        
        if (!$activeMatch) {
            return response()->json(['error' => 'No active match found'], 404);
        }
        
        $tournament = $activeMatch->tournament;
        
        // Auto-select matchup teams based on top fraggers of the active match
        $stats = $activeMatch->matchStats()->with(['tournamentTeam', 'players'])->get();
        if ($stats->isEmpty()) {
            return response()->json(['error' => 'No teams registered in the active match'], 404);
        }
        
        $playersColl = collect();
        foreach ($stats as $stat) {
            foreach ($stat->players as $player) {
                $playersColl->push([
                    'team_id' => $stat->tournament_team_id,
                    'kills' => (int) $player->pivot->kills,
                ]);
            }
        }
        
        $sortedFraggers = $playersColl->sortByDesc('kills')->values();
        
        $team1Id = null;
        $team2Id = null;
        
        if ($sortedFraggers->isNotEmpty()) {
            $team1Id = $sortedFraggers->first()['team_id'];
            // Find the next fragger that belongs to a different team
            $nextDifferent = $sortedFraggers->first(fn($p) => $p['team_id'] !== $team1Id);
            if ($nextDifferent) {
                $team2Id = $nextDifferent['team_id'];
            }
        }
        
        // Fallbacks
        if (!$team1Id) {
            $team1Id = $stats->get(0)->tournament_team_id ?? null;
        }
        if (!$team2Id) {
            $team2Id = $stats->get(1)->tournament_team_id ?? ($stats->get(0)->tournament_team_id ?? null);
        }
        
        // Get matchup data
        $stat1 = $stats->firstWhere('tournament_team_id', $team1Id);
        $stat2 = $stats->firstWhere('tournament_team_id', $team2Id);
        
        // Let's compute overall tournament statistics for both teams
        $round = $activeMatch->tournamentRound;
        if ($round) {
            $allMatches = $round->tournamentMatches()->get();
        } else {
            $allMatches = $tournament->tournamentMatches()->get();
        }
        
        $team1Overall = ['points' => 0, 'kills' => 0, 'wwcd' => 0];
        $team2Overall = ['points' => 0, 'kills' => 0, 'wwcd' => 0];
        
        foreach ($allMatches as $match) {
            $mStats = $match->matchStats()->get();
            $s1 = $mStats->firstWhere('tournament_team_id', $team1Id);
            $s2 = $mStats->firstWhere('tournament_team_id', $team2Id);
            
            if ($s1) {
                $team1Overall['points'] += $s1->points;
                $team1Overall['kills'] += $s1->kills;
                if ($s1->placement === 1) $team1Overall['wwcd']++;
            }
            if ($s2) {
                $team2Overall['points'] += $s2->points;
                $team2Overall['kills'] += $s2->kills;
                if ($s2->placement === 1) $team2Overall['wwcd']++;
            }
        }
        
        $bgTypeKey = "bg_type_{$user->id}";
        $bgType = Cache::get($bgTypeKey, 'transparent');
        $customVideoKey = "custom_video_{$user->id}";
        $customVideo = Cache::get($customVideoKey);
        
        return view('screens.headToHead', compact('user', 'activeMatch', 'tournament', 'stat1', 'stat2', 'team1Overall', 'team2Overall', 'bgType', 'customVideo'));
    }

    public function topFraggers(Request $request)
    {
        $user = User::findOrFail($request->route('user_id'));
        $activeMatch = $user->getActiveMatch();
        
        if (!$activeMatch) {
            return response()->json(['error' => 'No active match found'], 404);
        }
        
        // Get all player stats in the active match
        $stats = $activeMatch->matchStats()->with(['players', 'tournamentTeam'])->get();
        $playersColl = collect();
        
        foreach ($stats as $stat) {
            foreach ($stat->players as $player) {
                $playersColl->push([
                    'ign' => $player->ign,
                    'name' => $player->name,
                    'role' => $player->role,
                    'image' => $player->image,
                    'kills' => (int) $player->pivot->kills,
                    'is_alive' => (bool) $player->pivot->is_alive,
                    'team_name' => $stat->tournamentTeam->name,
                    'team_logo' => $stat->tournamentTeam->logo_image,
                ]);
            }
        }
        
        $topFraggers = $playersColl->sortByDesc('kills')->take(5)->values();
        
        $bgTypeKey = "bg_type_{$user->id}";
        $bgType = Cache::get($bgTypeKey, 'transparent');
        $customVideoKey = "custom_video_{$user->id}";
        $customVideo = Cache::get($customVideoKey);
        
        return view('screens.topFraggers', compact('user', 'activeMatch', 'topFraggers', 'bgType', 'customVideo'));
    }

    public function pointSystem(Request $request)
    {
        $user = User::findOrFail($request->route('user_id'));
        $activeMatch = $user->getActiveMatch();
        $tournament = $activeMatch ? $activeMatch->tournament : null;
        
        // If there's no active match, pull the latest tournament
        if (!$tournament) {
            $tournament = \App\Models\Tournament::where('user_id', $user->id)->latest()->first();
        }

        $tournamentSetting = $tournament ? $tournament->tournamentSettings()->first() : null;
        $placementPoints = $tournamentSetting 
            ? $tournamentSetting->tournamentSettingPlacementPoints()->orderBy('placement')->get()
            : collect();

        $bgTypeKey = "bg_type_{$user->id}";
        $bgType = Cache::get($bgTypeKey, 'transparent');
        $customVideoKey = "custom_video_{$user->id}";
        $customVideo = Cache::get($customVideoKey);

        return view('screens.pointSystem', compact('activeMatch', 'user', 'tournament', 'tournamentSetting', 'placementPoints', 'bgType', 'customVideo'));
    }

    public function mapPool(Request $request)
    {
        $user = User::findOrFail($request->route('user_id'));
        $activeMatch = $user->getActiveMatch();
        $tournament = $activeMatch->tournament;
        
        $currentRound = $activeMatch->tournamentRound;
        if ($currentRound) {
            $matches = $currentRound->tournamentMatches()->with('matchStats.tournamentTeam')->orderBy('id')->get();
        } else {
            $matches = $tournament->tournamentMatches()->with('matchStats.tournamentTeam')->orderBy('id')->get();
        }

        $bgTypeKey = "bg_type_{$user->id}";
        $bgType = Cache::get($bgTypeKey, 'transparent');
        $customVideoKey = "custom_video_{$user->id}";
        $customVideo = Cache::get($customVideoKey);

        return view('screens.mapPool', compact('activeMatch', 'user', 'tournament', 'matches', 'currentRound', 'bgType', 'customVideo'));
    }

    /**
     * Safely attempt to broadcast an event without throwing a 500 if
     * the WebSocket / Pusher connection is unavailable.
     */
    private function safeBroadcast(\Illuminate\Contracts\Broadcasting\ShouldBroadcast $event): void
    {
        try {
            broadcast($event);
        } catch (\Throwable $e) {
            \Illuminate\Support\Facades\Log::warning('[Broadcasting] Failed to broadcast event: ' . $e->getMessage());
        }
    }
}
