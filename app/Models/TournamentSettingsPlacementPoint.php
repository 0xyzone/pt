<?php

namespace App\Models;

use App\Models\TournamentSetting;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TournamentSettingsPlacementPoint extends Model
{
    protected $table = 'tournament_placement';
    /**
     * Get the tournamentSetting that owns the TournamentSettingsPlacementPoint
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function tournamentSetting(): BelongsTo
    {
        return $this->belongsTo(TournamentSetting::class, 'tournament_setting_id', 'id');
    }
}
