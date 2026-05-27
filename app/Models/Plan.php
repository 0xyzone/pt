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
        'price',
        'duration_value',
        'duration_period',
        'features',
        'is_active',
        'sort_order',
    ];

    /**
     * Get the dynamically generated price display.
     */
    public function getPriceDisplayAttribute(): string
    {
        if (!$this->price || $this->price <= 0) {
            return 'Free';
        }

        $formattedPrice = number_format($this->price);
        
        if (!$this->duration_value || !$this->duration_period) {
            return "NPR {$formattedPrice}"; // Lifetime
        }

        $periodMap = [
            'days' => 'day',
            'weeks' => 'week',
            'months' => 'mo',
            'years' => 'yr',
        ];

        $period = $periodMap[$this->duration_period] ?? $this->duration_period;
        
        if ($this->duration_value > 1) {
            return "NPR {$formattedPrice} / {$this->duration_value} {$period}";
        }

        return "NPR {$formattedPrice} / {$period}";
    }

    /**
     * Calculate the end date for a subscription to this plan starting from a given date.
     * Returns null if the plan is lifetime/unlimited.
     *
     * @param \Carbon\Carbon|null $startDate
     * @return \Carbon\Carbon|null
     */
    public function calculateEndDate(?\Carbon\Carbon $startDate = null): ?\Carbon\Carbon
    {
        if (!$this->duration_value || !$this->duration_period) {
            return null; // Lifetime plan
        }

        $date = $startDate ? $startDate->copy() : now();

        return match ($this->duration_period) {
            'days' => $date->addDays($this->duration_value),
            'weeks' => $date->addWeeks($this->duration_value),
            'months' => $date->addMonths($this->duration_value),
            'years' => $date->addYears($this->duration_value),
            default => null,
        };
    }

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
