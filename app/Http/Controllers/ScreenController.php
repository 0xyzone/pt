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

        return view('screens.postMatch', compact('activeMatch'));
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

        return view('screens.overallRanking', compact('tournament', 'rankings', 'activeMatch', 'currentRound'));
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

        $timerState = [
            'duration' => (int)Cache::get($durationKey, 10),
            'status' => Cache::get($statusKey, 'stopped'),
            'endsAt' => (int)Cache::get($endsAtKey, 0),
            'remainingSeconds' => (int)Cache::get($remainingKey, 600),
            'visible' => (bool)Cache::get($visibleKey, true),
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

        $timerState = [
            'duration' => (int)Cache::get($durationKey, 10),
            'status' => Cache::get($statusKey, 'stopped'),
            'endsAt' => (int)Cache::get($endsAtKey, 0),
            'remainingSeconds' => (int)Cache::get($remainingKey, 600),
            'visible' => (bool)Cache::get($visibleKey, true),
        ];

        $bgTypeKey = "bg_type_{$user->id}";
        $bgType = Cache::get($bgTypeKey, 'transparent');
        $customVideoKey = "custom_video_{$user->id}";
        $customVideo = Cache::get($customVideoKey);

        return view('screens.controlPanel', compact('user', 'timerState', 'bgType', 'customVideo'));
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

        return view('screens.statsControl', compact('user', 'activeMatch', 'placementOptions', 'teamSlots'));
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

        $timerState = [
            'duration' => (int)Cache::get($durationKey, 10),
            'status' => Cache::get($statusKey, 'stopped'),
            'endsAt' => (int)Cache::get($endsAtKey, 0),
            'remainingSeconds' => (int)Cache::get($remainingKey, 600),
            'visible' => (bool)Cache::get($visibleKey, true),
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

        $timerState = [
            'duration' => (int)Cache::get($durationKey, 10),
            'status' => Cache::get($statusKey, 'stopped'),
            'endsAt' => (int)Cache::get($endsAtKey, 0),
            'remainingSeconds' => (int)Cache::get($remainingKey, 600),
            'visible' => (bool)Cache::get($visibleKey, true),
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
        
        $durationKey = "timer_duration_{$userId}";
        $statusKey = "timer_status_{$userId}";
        $endsAtKey = "timer_ends_at_{$userId}";
        $remainingKey = "timer_remaining_{$userId}";
        $visibleKey = "timer_visible_{$userId}";
        
        // Defaults
        $duration = (int)Cache::get($durationKey, 10);
        $status = Cache::get($statusKey, 'stopped');
        $endsAt = (int)Cache::get($endsAtKey, 0);
        $remaining = (int)Cache::get($remainingKey, $duration * 60);
        $visible = (bool)Cache::get($visibleKey, true);
        
        if ($action === 'set-duration') {
            $duration = (int)$request->input('duration', 10);
            if ($duration < 1) $duration = 1;
            Cache::put($durationKey, $duration, 86400);
            
            // If stopped, reset remaining seconds to new duration
            if ($status === 'stopped') {
                $remaining = $duration * 60;
                Cache::put($remainingKey, $remaining, 86400);
            } else if ($status === 'running') {
                // Adjust active running timer
                $endsAt = time() + ($duration * 60);
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
            $status = 'stopped';
            $remaining = $duration * 60;
            $endsAt = 0;
            Cache::put($statusKey, $status, 86400);
            Cache::put($remainingKey, $remaining, 86400);
            Cache::put($endsAtKey, $endsAt, 86400);
        } elseif ($action === 'toggle-visibility') {
            $visible = $request->input('visible') == '1';
            Cache::put($visibleKey, $visible, 86400);
        }
        
        // Broadcast the real-time event to all overlays
        $this->safeBroadcast(new \App\Events\TimerUpdated($userId, $status, $duration, $remaining, $endsAt, $visible));
        
        return response()->json([
            'success' => true,
            'status' => $status,
            'duration' => $duration,
            'remainingSeconds' => $remaining,
            'endsAt' => $endsAt,
            'visible' => $visible
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
