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

    protected static function booted()
    {
        static::saved(function ($match) {
            if ($match->tournament) {
                broadcast(new \App\Events\TournamentMatchUpdated($match));
            }

            // Sync map changes back to the round settings
            $round = $match->tournamentRound;
            if ($round) {
                $matches = $round->tournamentMatches()->orderBy('id')->pluck('id')->toArray();
                $index = array_search($match->id, $matches);
                if ($index !== false) {
                    $maps = $round->maps ?? [];
                    if (isset($maps[$index])) {
                        if (($maps[$index]['map'] ?? '') !== $match->map) {
                            $maps[$index]['map'] = $match->map;
                            \App\Models\TournamentRound::withoutEvents(function () use ($round, $maps) {
                                $round->update(['maps' => $maps]);
                            });
                        }
                    }
                }
            }
        });

        static::deleted(function ($match) {
            // Remove the corresponding map entry from the round when a match is deleted
            $round = $match->tournamentRound;
            if (!$round) {
                return;
            }

            // Find this match's position among its siblings (ordered by id)
            $matches = $round->tournamentMatches()->orderBy('id')->pluck('id')->toArray();
            $index = array_search($match->id, $matches);

            if ($index !== false) {
                $maps = $round->maps ?? [];
                // Remove the map at that position and re-index
                array_splice($maps, $index, 1);
                // Update the round without re-triggering autoGenerateMatches
                \App\Models\TournamentRound::withoutEvents(function () use ($round, $maps) {
                    $round->update(['maps' => $maps]);
                });
            }
        });
    }

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
     * Get the tournamentRound that owns the TournamentMatch
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function tournamentRound(): BelongsTo
    {
        return $this->belongsTo(TournamentRound::class);
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
