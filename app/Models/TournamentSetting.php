<?php

namespace App\Models;

use App\Models\Tournament;
use App\Models\TournamentSettingsPlacementPoint;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class TournamentSetting extends Model
{
    protected $fillable = [
        'tournament_id',
        'kill_points',
        'obs_password',
    ];

    /**
     * Get the tournament that owns the TournamentSetting
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function tournament(): BelongsTo
    {
        return $this->belongsTo(Tournament::class);
    }

    /**
     * Get all of the tournamentSettingPlacementPoints for the TournamentSetting
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function tournamentSettingPlacementPoints(): HasMany
    {
        return $this->hasMany(TournamentSettingsPlacementPoint::class, 'tournament_setting_id', 'id');
    }
}
