<?php

namespace App\Models;

use App\Models\Caster;
use App\Models\Plan;
use App\Models\Subscription;
use App\Models\SubscriptionRequest;
use App\Models\Tournament;
use Filament\Models\Contracts\FilamentUser;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Rarq\FilamentQuickNotes\Traits\HasFilamentQuickNotes;
use Spatie\LaravelPasskeys\Models\Concerns\HasPasskeys;
use Spatie\LaravelPasskeys\Models\Concerns\InteractsWithPasskeys;

#[Fillable(['name', 'email', 'password'])]
#[Hidden(['password', 'remember_token'])]
class User extends Authenticatable implements MustVerifyEmail, HasPasskeys, FilamentUser
{
    use HasFactory, Notifiable, InteractsWithPasskeys, HasFilamentQuickNotes;

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /**
     * Get all of the tournaments for the User
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function tournaments(): HasMany
    {
        return $this->hasMany(Tournament::class);
    }

    /**
     * Get the user's active subscription (latest active one).
     */
    public function activeSubscription(): HasOne
    {
        return $this->hasOne(Subscription::class)
            ->where('status', 'active')
            ->where(fn ($q) => $q->whereNull('ends_at')->orWhere('ends_at', '>', now()))
            ->latestOfMany();
    }

    /**
     * Get the user's latest subscription (active, expired, or cancelled).
     */
    public function latestSubscription(): HasOne
    {
        return $this->hasOne(Subscription::class)->latestOfMany();
    }

    /**
     * All subscriptions history.
     */
    public function subscriptions(): HasMany
    {
        return $this->hasMany(Subscription::class);
    }

    /**
     * Get all of the subscription requests for the User.
     */
    public function subscriptionRequests(): HasMany
    {
        return $this->hasMany(SubscriptionRequest::class);
    }

    /**
     * Get the current active Plan, or null if no subscription.
     */
    public function activePlan(): ?Plan
    {
        return $this->activeSubscription?->plan;
    }

    /**
     * Check if the user's current plan has a boolean feature enabled.
     */
    public function canUseFeature(string $feature): bool
    {
        $plan = $this->activePlan();
        if ($plan === null) {
            return false;
        }
        return $plan->hasFeature($feature);
    }

    /**
     * Get a numeric limit from the user's current plan.
     * Returns 0 if no plan, -1 means unlimited.
     */
    public function getFeatureLimit(string $feature, int $default = 0): int
    {
        $plan = $this->activePlan();
        if ($plan === null) {
            return $default;
        }
        return $plan->getLimit($feature, $default);
    }

    public function canAccessPanel(\Filament\Panel $panel): bool
    {
        $panel = $panel->getId();
        if ($panel == 'admin') {
            return str_ends_with($this->email, '@suminshrestha.com.np') || str_ends_with($this->email, '@admin.com');
        } else if ($panel == 'maidan') {
            return true;
        }
        return false;
    }

    public function getActiveMatch()
    {
        $activeMatch = TournamentMatch::whereHas('tournament', function ($query) {
            $query->where('user_id', $this->id)->where('is_active', true);
        })->where('is_active', true)->with(['matchStats', 'tournament', 'tournament.tournamentTeams'])->first();
        if (!$activeMatch) {
            abort(404, 'No active match found for this user.');
        }

        return $activeMatch;
    }

    /**
     * Get all of the casters for the User
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function casters(): HasMany
    {
        return $this->hasMany(Caster::class);
    }
}
