<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Models\Player;

class TournamentRound extends Model
{
    protected $fillable = ['tournament_id', 'name', 'maps'];

    protected $casts = [
        'maps' => 'array',
    ];

    protected static function booted()
    {
        static::saved(function ($round) {
            $round->autoGenerateMatches();
        });
    }

    /**
     * Auto generate matches for maps inside this round and populate teams and players.
     */
    public function autoGenerateMatches()
    {
        $existingMatches = $this->tournamentMatches()->orderBy('id')->get();
        $maps = $this->maps ?? [];
        
        $existingMatchCount = $existingMatches->count();
        $mapsCount = count($maps);
        
        // 1. Synchronize map changes for existing matches
        for ($i = 0; $i < min($existingMatchCount, $mapsCount); $i++) {
            $match = $existingMatches[$i];
            $mapItem = $maps[$i];
            $mapName = $mapItem['map'] ?? 'erangle';
            
            if ($match->map !== $mapName) {
                \App\Models\TournamentMatch::withoutEvents(function () use ($match, $mapName) {
                    $match->update(['map' => $mapName]);
                });
            }
        }
        
        // 2. Generate new matches if maps array is larger than existing matches count
        if ($existingMatchCount < $mapsCount) {
            $tournament = $this->tournament;
            $teams = $tournament ? $tournament->tournamentTeams()->get() : collect();
            
            for ($i = $existingMatchCount; $i < $mapsCount; $i++) {
                $mapItem = $maps[$i];
                $mapName = $mapItem['map'] ?? 'erangle';
                $matchIndex = $i + 1;
                
                $match = $this->tournamentMatches()->create([
                    'tournament_id' => $this->tournament_id,
                    'name' => "{$this->name} - Match {$matchIndex}",
                    'map' => $mapName,
                    'is_active' => false,
                    'is_completed' => false,
                    'match_date' => now()->toDateString(),
                    'match_time' => now()->toTimeString(),
                ]);
                
                // Populate teams and players
                foreach ($teams as $team) {
                    $playerIds = Player::where('tournament_team_id', $team->id)->pluck('id')->toArray();
                    
                    $matchStat = $match->matchStats()->create([
                        'tournament_team_id' => $team->id,
                        'alive' => count($playerIds) > 0 ? count($playerIds) : 4,
                        'kills' => 0,
                        'placement' => 0,
                        'is_winner' => false,
                        'points' => 0,
                    ]);
                    
                    if (!empty($playerIds)) {
                        $matchStat->players()->sync($playerIds);
                    }
                    $matchStat->recalculateTotals();
                }
            }
        }

        // 3. Delete matches that no longer have a corresponding map entry
        if ($existingMatchCount > $mapsCount) {
            $matchesToDelete = $existingMatches->slice($mapsCount);
            foreach ($matchesToDelete as $match) {
                // Delete related match stats and their player pivots first
                foreach ($match->matchStats as $stat) {
                    $stat->players()->detach();
                    $stat->delete();
                }
                $match->delete();
            }
        }
    }

    /**
     * Get the tournament that owns the TournamentRound.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function tournament(): BelongsTo
    {
        return $this->belongsTo(Tournament::class);
    }

    /**
     * Get all of the tournament matches for the TournamentRound.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function tournamentMatches(): HasMany
    {
        return $this->hasMany(TournamentMatch::class);
    }
}
