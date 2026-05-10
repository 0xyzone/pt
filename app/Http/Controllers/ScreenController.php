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

    public function switchObsView()
    {
        $userId = request()->route('user_id');
        $viewName = request()->input('view'); // 'empty', 'postmatch', 'overallranking'
        broadcast(new \App\Events\ObsViewSwitched($userId, $viewName));
        return back()->with('status', 'View switched to ' . $viewName);
    }
}
