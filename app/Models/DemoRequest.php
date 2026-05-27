<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class DemoRequest extends Model
{
    protected $fillable = [
        'name',
        'email',
        'organization',
        'phone',
        'message',
        'status',
        'admin_notes',
        'ip_address',
    ];

    public const STATUSES = [
        'new'       => 'New',
        'contacted' => 'Contacted',
        'converted' => 'Converted',
        'rejected'  => 'Rejected',
    ];

    public const STATUS_COLORS = [
        'new'       => 'warning',
        'contacted' => 'info',
        'converted' => 'success',
        'rejected'  => 'danger',
    ];

    public function getStatusLabelAttribute(): string
    {
        return self::STATUSES[$this->status] ?? ucfirst($this->status);
    }

    public function getStatusColorAttribute(): string
    {
        return self::STATUS_COLORS[$this->status] ?? 'gray';
    }

    // ─── Scopes ───────────────────────────────────────────────
    public function scopeNew(Builder $query): Builder
    {
        return $query->where('status', 'new');
    }

    public function scopeContacted(Builder $query): Builder
    {
        return $query->where('status', 'contacted');
    }

    public function scopeConverted(Builder $query): Builder
    {
        return $query->where('status', 'converted');
    }
}
