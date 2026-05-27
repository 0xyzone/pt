<?php

namespace App\Filament\Maidan\Widgets;

use App\Models\Plan;
use App\Models\Subscription;
use App\Models\User;
use App\Models\SubscriptionRequest;
use App\Services\SubscriptionService;
use Filament\Notifications\Notification;
use Filament\Widgets\Widget;
use Illuminate\Support\Facades\Auth;
use Livewire\WithFileUploads;

class SubscriptionOverviewWidget extends Widget
{
    use WithFileUploads;
    protected static ?int $sort = -3; // Display at the very top of Maidan panel

    protected int|string|array $columnSpan = 'full';

    protected string $view = 'filament.maidan.widgets.subscription-overview-widget';

    // Form inputs
    public ?string $selectedPlanSlug = '';
    public \Livewire\Features\SupportFileUploads\TemporaryUploadedFile|null $transactionScreenshot = null;

    public function mount(): void
    {
        $plans = $this->getAvailablePlans();
        if (!empty($plans)) {
            $this->selectedPlanSlug = $plans[0]->slug;
        }
    }

    /**
     * Get user's current subscription.
     */
    public function getLatestSubscription(): ?Subscription
    {
        return Auth::user()->latestSubscription;
    }

    /**
     * Get active subscription.
     */
    public function getActiveSubscription(): ?Subscription
    {
        return Auth::user()->activeSubscription;
    }

    /**
     * Get all available paid plans.
     */
    public function getAvailablePlans()
    {
        return Plan::where('is_active', true)->orderBy('sort_order')->get();
    }

    /**
     * Calculate current tournament usage.
     */
    public function getTournamentUsage(): array
    {
        /** @var User $user */
        $user = Auth::user();
        $current = $user->tournaments()->count();
        $limit = SubscriptionService::limit($user, 'max_tournaments', 0);

        return [
            'current' => $current,
            'limit' => $limit,
            'percent' => $limit > 0 ? min(100, round(($current / $limit) * 100)) : ($limit === -1 ? 0 : 100),
            'display' => $limit === -1 ? "{$current} / Unlimited" : "{$current} / {$limit}",
        ];
    }

    /**
     * Submit subscription upgrade/renew request.
     */
    public function submitRequest(): void
    {
        $user = Auth::user();
        $plan = Plan::where('slug', $this->selectedPlanSlug)->first();

        if (!$plan) {
            Notification::make()
                ->title('Invalid Plan Selected')
                ->danger()
                ->send();
            return;
        }

        $this->validate([
            'selectedPlanSlug' => 'required|exists:plans,slug',
            'transactionScreenshot' => 'required|image|max:5120', // max 5MB
        ]);

        // Store screenshot
        $screenshotPath = $this->transactionScreenshot->store('subscription-screenshots', 'public');

        // Create subscription request record
        SubscriptionRequest::create([
            'user_id' => $user->id,
            'plan_id' => $plan->id,
            'screenshot_path' => $screenshotPath,
            'status' => 'pending',
        ]);

        // Find all admin users
        $admins = User::all()->filter(function ($u) {
            return str_ends_with($u->email, '@suminshrestha.com.np') || str_ends_with($u->email, '@admin.com');
        });

        // Send Filament Database Notification to admins
        if ($admins->isNotEmpty()) {
            Notification::make()
                ->title('Subscription Request Received')
                ->body("User **{$user->name}** ({$user->email}) requested to subscribe/renew the **{$plan->name}** plan. A transaction screenshot has been uploaded.")
                ->icon('heroicon-o-credit-card')
                ->iconColor('success')
                ->actions([
                    \Filament\Actions\Action::make('manage')
                        ->label('Review Requests')
                        ->url('/admin/subscription-requests')
                        ->button(),
                ])
                ->sendToDatabase($admins);
        }

        // Notify user of success
        Notification::make()
            ->title('Subscription Request Sent!')
            ->body("Your request for the **{$plan->name}** has been sent to our administrator along with the transaction screenshot. We will review it shortly.")
            ->success()
            ->send();

        // Clear screenshot input
        $this->transactionScreenshot = null;
    }

    /**
     * Get user's pending subscription requests.
     */
    public function getPendingRequests()
    {
        return SubscriptionRequest::with('plan')
            ->where('user_id', Auth::id())
            ->where('status', 'pending')
            ->orderBy('created_at', 'desc')
            ->get();
    }

    /**
     * Cancel a pending subscription request.
     */
    public function cancelRequest(int $requestId): void
    {
        $request = SubscriptionRequest::where('id', $requestId)
            ->where('user_id', Auth::id())
            ->where('status', 'pending')
            ->first();

        if ($request) {
            // Delete screenshot from storage if it exists
            if ($request->screenshot_path) {
                \Illuminate\Support\Facades\Storage::disk('public')->delete($request->screenshot_path);
            }

            // Delete the request
            $request->delete();

            Notification::make()
                ->title('Request Cancelled')
                ->body('Your pending subscription request has been cancelled successfully.')
                ->success()
                ->send();
        }
    }
}
