<?php

namespace App\Services;

use App\Models\User;

class SubscriptionService
{
    /**
     * Check if the user's active plan has a boolean feature.
     */
    public static function can(User $user, string $feature): bool
    {
        return $user->canUseFeature($feature);
    }

    /**
     * Get a numeric limit for the user. Returns -1 for unlimited, 0 if no plan.
     */
    public static function limit(User $user, string $feature, int $default = 0): int
    {
        return $user->getFeatureLimit($feature, $default);
    }

    /**
     * Check if the user is within a count-based limit.
     * Pass the current count and the feature key.
     * Returns true if allowed (under limit), false if at/over limit.
     */
    public static function withinLimit(User $user, string $feature, int $currentCount): bool
    {
        $limit = self::limit($user, $feature, 0);
        if ($limit === -1) {
            return true; // Unlimited
        }
        return $currentCount < $limit;
    }

    /**
     * Check if the user has any active subscription at all.
     */
    public static function hasActiveSubscription(User $user): bool
    {
        return $user->activeSubscription !== null;
    }

    /**
     * Get the user's current plan name, or "No Plan" if none.
     */
    public static function planName(User $user): string
    {
        return $user->activePlan()?->name ?? 'No Plan';
    }
}
