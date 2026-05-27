<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\User;
use App\Services\SubscriptionService;
use Symfony\Component\HttpFoundation\Response;

class CheckSubscriptionOverlay
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $userId = $request->route('user_id');
        if (!$userId) {
            return $next($request);
        }

        $user = User::find($userId);
        if (!$user) {
            abort(404, 'User not found.');
        }

        // Check if user has active subscription and obs_overlays feature
        if (!SubscriptionService::hasActiveSubscription($user) || !SubscriptionService::can($user, 'obs_overlays')) {
            if ($request->expectsJson() || $request->ajax() || $request->isMethod('POST')) {
                return response()->json([
                    'error' => 'Subscription inactive or feature locked. Please contact support.',
                ], 403);
            }

            return response()->view('errors.subscription_required', [
                'user' => $user
            ], 403);
        }

        return $next($request);
    }
}
