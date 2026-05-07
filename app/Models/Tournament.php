<?php

namespace App\Models;

use App\Models\TournamentMatch;
use App\Models\TournamentSetting;
use App\Models\TournamentTeam;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Tournament extends Model
{
    /**
     * Get the user that owns the Tournament
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get all of the tournament_teams for the Tournament
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function tournamentTeams(): HasMany
    {
        return $this->hasMany(TournamentTeam::class);
    }

    /**
     * Get all of the tournament_matches for the Tournament
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function tournamentMatches(): HasMany
    {
        return $this->hasMany(TournamentMatch::class);
    }
    /**
     * Get all of the tournamentSettings for the Tournament
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function tournamentSettings(): HasMany
    {
        return $this->hasMany(TournamentSetting::class);
    }
}
