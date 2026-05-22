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

    public function players(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
        return $this->belongsToMany(Player::class, 'match_stat_player')
            ->withPivot(['kills', 'is_alive']);
    }

    public function recalculateTotals(): void
    {
        $this->load('players');
        
        $totalKills = $this->players->sum('pivot.kills');
        $aliveCount = $this->players->filter(fn($player) => $player->pivot->is_alive)->count();
        
        $this->kills = $totalKills;
        $this->alive = $aliveCount;
        
        // Recalculate points
        $tournamentSetting = $this->tournamentMatch?->tournament?->tournamentSettings->first();
        $points = 0;
        if ($tournamentSetting) {
            $killPoints = ($tournamentSetting->kill_points ?? 0) * $this->kills;
            $placementPoints = $tournamentSetting->tournamentSettingPlacementPoints
                ->where('placement', $this->placement)
                ->first()?->points ?? 0;
            $points = $killPoints + $placementPoints;
        }
        $this->points = $points;
        $this->save();
    }
}
