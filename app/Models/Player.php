<?php

namespace App\Models;

use App\Models\TournamentTeam;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Player extends Model
{
    protected $guarded = [];

    /**
     * Get the tournament team that owns the Player.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function tournamentTeam(): BelongsTo
    {
        return $this->belongsTo(TournamentTeam::class);
    }

    public function matchStats(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
        return $this->belongsToMany(MatchStat::class, 'match_stat_player')
            ->withPivot(['kills', 'is_alive']);
    }
}
