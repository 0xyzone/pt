<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TournamentRoadmap extends Model
{
    protected $fillable = [
        'tournament_id',
        'title',
        'description',
        'steps',
    ];

    protected $casts = [
        'steps' => 'array',
    ];

    /**
     * Get the tournament that owns the roadmap.
     */
    public function tournament(): BelongsTo
    {
        return $this->belongsTo(Tournament::class);
    }
}
