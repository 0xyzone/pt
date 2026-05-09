<?php

namespace App\Http\Controllers;

use App\Models\TournamentMatch;
use App\Models\User;
// use Illuminate\Http\Request;

class ApiController extends Controller
{
    public function getAll($user_id)
    {
        $tournament = User::find($user_id)->tournaments()->where('is_active', true)->with(['tournamentMatches', 'tournamentTeams'])->get();

        $data['tournament'] = $tournament;
        $activeMatch = TournamentMatch::whereHas('tournament', function ($query) use ($user_id) {
            $query->where('user_id', $user_id)->where('is_active', true);
        })->where('is_active', true)->first()->with('matchStats')->get();
        $data['active_match'] = $activeMatch;
        return response()->json($data);
    }

    public function activeMatch($user_id)
    {
        $activeMatch = TournamentMatch::whereHas('tournament', function ($query) use ($user_id) {
            $query->where('user_id', $user_id)->where('is_active', true);
        })->where('is_active', true)->with(['matchStats', 'tournament', 'tournament.tournamentTeams'])->first();

        return response()->json($activeMatch);
    }
}
