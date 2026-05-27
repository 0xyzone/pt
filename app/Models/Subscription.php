<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Subscription extends Model
{
    protected $fillable = [
        'user_id',
        'plan_id',
        'status',
        'starts_at',
        'ends_at',
        'granted_by',
        'notes',
    ];

    protected $casts = [
        'starts_at' => 'datetime',
        'ends_at'   => 'datetime',
    ];

    /**
     * Check if this subscription is currently active.
     */
    public function isActive(): bool
    {
        if ($this->status === 'cancelled' || $this->status === 'expired') {
            return false;
        }
        if ($this->ends_at !== null && $this->ends_at->isPast()) {
            return false;
        }
        return true;
    }

    /**
     * Get the number of days remaining. Returns null if no expiry.
     */
    public function daysRemaining(): ?int
    {
        if ($this->ends_at === null) {
            return null; // No expiry
        }
        return (int) max(0, now()->diffInDays($this->ends_at, false));
    }

    // ─── Scopes ───────────────────────────────────────────────
    public function scopeActive(Builder $query): Builder
    {
        return $query->where('status', 'active')
            ->where(fn ($q) => $q->whereNull('ends_at')->orWhere('ends_at', '>', now()));
    }

    public function scopeExpired(Builder $query): Builder
    {
        return $query->where(fn ($q) =>
            $q->where('status', 'expired')
              ->orWhere(fn ($q2) => $q2->whereNotNull('ends_at')->where('ends_at', '<=', now()))
        );
    }

    // ─── Relations ────────────────────────────────────────────
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function plan(): BelongsTo
    {
        return $this->belongsTo(Plan::class);
    }

    public function grantedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'granted_by');
    }
}
