<?php

namespace Tests\Feature;

use App\Models\Plan;
use App\Models\Subscription;
use App\Models\User;
use App\Services\SubscriptionService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SubscriptionServiceTest extends TestCase
{
    use RefreshDatabase;

    private User $user;
    private Plan $oneDayPlan;
    private Plan $oneMonthPlan;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->create();

        $this->oneDayPlan = Plan::create([
            'name' => '1 Day Pass',
            'slug' => '1-day-pass',
            'price' => 199,
            'is_active' => true,
            'features' => [
                'max_tournaments' => 1,
                'max_teams' => 8,
                'roadmap_overlay' => false,
                'obs_overlays' => true,
            ]
        ]);

        $this->oneMonthPlan = Plan::create([
            'name' => '1 Month Plan',
            'slug' => '1-month-plan',
            'price' => 1999,
            'is_active' => true,
            'features' => [
                'max_tournaments' => 20,
                'max_teams' => 50,
                'roadmap_overlay' => true,
                'obs_overlays' => true,
            ]
        ]);
    }

    public function test_user_without_subscription_has_no_active_plan(): void
    {
        $this->assertFalse(SubscriptionService::hasActiveSubscription($this->user));
        $this->assertEquals('No Plan', SubscriptionService::planName($this->user));
        $this->assertFalse(SubscriptionService::can($this->user, 'obs_overlays'));
        $this->assertEquals(0, SubscriptionService::limit($this->user, 'max_tournaments'));
    }

    public function test_user_with_active_subscription_has_correct_features(): void
    {
        Subscription::create([
            'user_id' => $this->user->id,
            'plan_id' => $this->oneDayPlan->id,
            'status' => 'active',
            'starts_at' => now(),
            'ends_at' => now()->addMonth(),
        ]);

        $this->assertTrue(SubscriptionService::hasActiveSubscription($this->user));
        $this->assertEquals('1 Day Pass', SubscriptionService::planName($this->user));
        $this->assertTrue(SubscriptionService::can($this->user, 'obs_overlays'));
        $this->assertFalse(SubscriptionService::can($this->user, 'roadmap_overlay'));
        $this->assertEquals(1, SubscriptionService::limit($this->user, 'max_tournaments'));
        $this->assertEquals(8, SubscriptionService::limit($this->user, 'max_teams'));
    }

    public function test_user_reaches_limits(): void
    {
        Subscription::create([
            'user_id' => $this->user->id,
            'plan_id' => $this->oneDayPlan->id,
            'status' => 'active',
            'starts_at' => now(),
            'ends_at' => now()->addMonth(),
        ]);

        $this->assertTrue(SubscriptionService::withinLimit($this->user, 'max_tournaments', 0));
        $this->assertFalse(SubscriptionService::withinLimit($this->user, 'max_tournaments', 1));
    }

    public function test_expired_subscription_is_inactive(): void
    {
        Subscription::create([
            'user_id' => $this->user->id,
            'plan_id' => $this->oneDayPlan->id,
            'status' => 'active',
            'starts_at' => now()->subMonths(2),
            'ends_at' => now()->subMonth(),
        ]);

        $this->assertFalse(SubscriptionService::hasActiveSubscription($this->user));
        $this->assertEquals('No Plan', SubscriptionService::planName($this->user));
    }

    public function test_cancelled_subscription_is_inactive(): void
    {
        Subscription::create([
            'user_id' => $this->user->id,
            'plan_id' => $this->oneMonthPlan->id,
            'status' => 'cancelled',
            'starts_at' => now(),
            'ends_at' => now()->addMonth(),
        ]);

        $this->assertFalse(SubscriptionService::hasActiveSubscription($this->user));
    }
}
