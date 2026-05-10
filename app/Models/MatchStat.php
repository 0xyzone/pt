<?php

namespace App\Models;

use App\Models\TournamentMatch;
use App\Models\TournamentTeam;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MatchStat extends Model
{
    /**
     * The event map for the model.
     *
     * @var array
     */
    protected $dispatchesEvents = [
        'saved' => \App\Events\MatchStatsUpdated::class,
        'deleted' => \App\Events\MatchStatsUpdated::class,
    ];
    /**
     * Get the tournamentMatch that owns the MatchStat
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function tournamentMatch(): BelongsTo
    {
        return $this->belongsTo(TournamentMatch::class);
    }

    /**
     * Get the tournamentTeam that owns the MatchStat
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function tournamentTeam(): BelongsTo
    {
        return $this->belongsTo(TournamentTeam::class);
    }
}
