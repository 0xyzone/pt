<?php

namespace App\Models;

use App\Models\MatchStat;
use App\Models\Tournament;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class TournamentTeam extends Model
{
    /**
     * Get the tournament that owns the TournamentTeam
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function tournament(): BelongsTo
    {
        return $this->belongsTo(Tournament::class);
    }

    /**
     * Get all of the matchStats for the TournamentTeam
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function matchStats(): HasMany
    {
        return $this->hasMany(MatchStat::class);
    }
}
