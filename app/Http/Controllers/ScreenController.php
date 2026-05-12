<?php

namespace App\Http\Controllers;

use App\Models\User;

class ScreenController extends Controller
{
    public function activematch()
    {
        $user = User::find(request()->route('user_id'));
        $activeMatch = $user->getActiveMatch();
        return view('screens.activeMatch', compact('activeMatch'));
    }
    
    public function postMatch()
    {
        $user = User::find(request()->route('user_id'));
        $activeMatch = $user->getActiveMatch();

        return view('screens.postMatch', compact('activeMatch'));
    }

    public function overallRanking()
    {
        $user = User::find(request()->route('user_id'));
        $activeMatch = $user->getActiveMatch();
        $tournament = $activeMatch->tournament;

        $teamsData = collect();

        foreach ($tournament->tournamentMatches as $match) {
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

        return view('screens.overallRanking', compact('tournament', 'rankings', 'activeMatch'));
    }

    public function teamElimination()
    {
        $user = User::findOrFail(request()->route('user_id'));
        $activeMatch = $user->getActiveMatch();
        return view('screens.teamElimination', compact('activeMatch', 'user'));
    }

    public function upcomingMatches()
    {
        $user = User::findOrFail(request()->route('user_id'));
        $activeMatch = $user->getActiveMatch();
        $tournament = $activeMatch->tournament;
        $matches = $tournament->tournamentMatches()->orderBy('match_date')->orderBy('match_time')->get();

        return view('screens.upcomingMatches', compact('activeMatch', 'user', 'tournament', 'matches'));
    }

    public function mapScreen()
    {
        $user = User::findOrFail(request()->route('user_id'));
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

    public function obsMaster()
    {
        $user = User::findOrFail(request()->route('user_id'));
        $activeMatch = $user->getActiveMatch();
        return view('screens.obsMaster', compact('activeMatch', 'user'));
    }

    public function controlPanel()
    {
        $user = User::findOrFail(request()->route('user_id'));
        return view('screens.controlPanel', compact('user'));
    }

    public function statsControl()
    {
        $user = User::findOrFail(request()->route('user_id'));
        $activeMatch = $user->getActiveMatch();
        
        if (!$activeMatch) {
            return redirect()->route('screens.controlpanel', ['user_id' => $user->id])
                ->with('status', 'No active match found. Please activate a match first.');
        }

        $activeMatch->load(['matchStats.tournamentTeam', 'tournament.tournamentSettings.tournamentSettingPlacementPoints']);
        $tournamentSetting = $activeMatch->tournament->tournamentSettings->first();
        $placementOptions = $tournamentSetting
            ? $tournamentSetting->tournamentSettingPlacementPoints->pluck('placement')->toArray()
            : [];
            
        // Map team IDs to their slot numbers (index + 2) based on ID order
        $orderedTeams = $activeMatch->tournament->tournamentTeams()->orderBy('id')->get();
        $teamSlots = $orderedTeams->pluck('id')->flip()->map(fn($i) => $i + 2);

        return view('screens.statsControl', compact('user', 'activeMatch', 'placementOptions', 'teamSlots'));
    }

    public function updateMatchStat()
    {
        $statId = request()->input('stat_id');
        $field = request()->input('field');
        $value = request()->input('value');

        $stat = \App\Models\MatchStat::findOrFail($statId);
        $stat->load(['tournamentMatch.tournament.tournamentSettings.tournamentSettingPlacementPoints', 'tournamentTeam']);

        $allowed = ['alive', 'kills', 'placement', 'is_winner'];
        if (!in_array($field, $allowed)) {
            return response()->json(['error' => 'Invalid field'], 422);
        }

        // Handle winner toggle: clear other winners first
        if ($field === 'is_winner' && $value) {
            \App\Models\MatchStat::where('tournament_match_id', $stat->tournament_match_id)
                ->where('id', '!=', $stat->id)
                ->update(['is_winner' => false]);
        }

        $stat->update([$field => $value]);

        // Recalculate points if kills or placement changed
        if (in_array($field, ['kills', 'placement'])) {
            $stat->refresh();
            $tournamentSetting = $stat->tournamentMatch?->tournament?->tournamentSettings->first();
            $points = 0;
            if ($tournamentSetting) {
                $killPoints = ($tournamentSetting->kill_points ?? 0) * $stat->kills;
                $placementPoints = $tournamentSetting->tournamentSettingPlacementPoints
                    ->where('placement', $stat->placement)
                    ->first()?->points ?? 0;
                $points = $killPoints + $placementPoints;
            }
            $stat->update(['points' => $points]);
        }

        // Fire elimination event if alive just hit 0
        if ($field === 'alive' && (int)$value === 0) {
            event(new \App\Events\TeamEliminated(
                $stat->tournamentTeam->name,
                $stat->tournamentTeam->logo_image,
                $stat->tournament_match_id
            ));
        }

        $stat->refresh();
        return response()->json(['success' => true, 'stat' => $stat->load('tournamentTeam')]);
    }

    public function switchObsView()
    {
        $userId = request()->route('user_id');
        $viewName = request()->input('view'); // 'empty', 'postmatch', 'overallranking'
        broadcast(new \App\Events\ObsViewSwitched($userId, $viewName));
        return back()->with('status', 'View switched to ' . $viewName);
    }

    public function toggleActiveMatchVisibility()
    {
        $userId = request()->route('user_id');
        $isVisible = request()->input('visible') == '1';
        broadcast(new \App\Events\ActiveMatchVisibilityToggled($userId, $isVisible));
        return back()->with('status', 'Active Match visibility toggled to ' . ($isVisible ? 'Visible' : 'Hidden'));
    }

    public function refreshScreens()
    {
        $userId = request()->route('user_id');
        broadcast(new \App\Events\RefreshScreensEvent($userId));
        return back()->with('status', 'Refresh signal sent to all screens.');
    }

    public function slotList()
    {
        $user = User::findOrFail(request()->route('user_id'));
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
}
