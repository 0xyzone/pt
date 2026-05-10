<?php

namespace App\Models;

use App\Models\MatchStat;
use App\Models\Tournament;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class TournamentMatch extends Model
{
    protected $guarded = [];
    /**
     * Get the tournament that owns the TournamentMatch
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function tournament(): BelongsTo
    {
        return $this->belongsTo(Tournament::class);
    }

    /**
     * Get all of the matchStats for the TournamentMatch
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function matchStats(): HasMany
    {
        return $this->hasMany(MatchStat::class);
    }
}
