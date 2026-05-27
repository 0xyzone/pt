<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Plan extends Model
{
    protected $fillable = [
        'name',
        'slug',
        'description',
        'price_display',
        'features',
        'is_active',
        'sort_order',
    ];

    protected $casts = [
        'features'  => 'array',
        'is_active' => 'boolean',
    ];

    /**
     * Default feature structure used when creating plans.
     */
    public static function defaultFeatures(): array
    {
        return [
            'max_tournaments'      => 1,    // int: max tournaments, -1 = unlimited
            'max_teams'            => 8,    // int: max teams per tournament, -1 = unlimited
            'roadmap_overlay'      => false, // bool
            'casters_management'   => false, // bool
            'player_management'    => false, // bool
            'obs_overlays'         => true,  // bool
            'websocket_sync'       => true,  // bool
            'custom_branding'      => false, // bool
        ];
    }

    /**
     * Check if this plan has a specific feature enabled.
     */
    public function hasFeature(string $key): bool
    {
        $features = $this->features ?? [];
        return (bool) ($features[$key] ?? false);
    }

    /**
     * Get a numeric limit from the plan features.
     * Returns -1 for unlimited.
     */
    public function getLimit(string $key, int $default = 0): int
    {
        $features = $this->features ?? [];
        return (int) ($features[$key] ?? $default);
    }

    // ─── Relations ────────────────────────────────────────────
    public function subscriptions(): HasMany
    {
        return $this->hasMany(Subscription::class);
    }
}
