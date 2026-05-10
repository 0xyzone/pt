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
                        'matches_played' => 0,
                    ]);
                }

                $data = $teamsData->get($stat->tournament_team_id);
                $data['total_points'] += $stat->points;
                $data['total_kills'] += $stat->kills;
                
                if ($match->is_completed) {
                    $data['matches_played'] += 1;
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
