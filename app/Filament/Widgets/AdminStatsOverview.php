<?php

namespace App\Filament\Widgets;

use App\Models\User;
use App\Models\Tournament;
use App\Models\SubscriptionRequest;
use App\Models\DemoRequest;
use App\Models\Subscription;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class AdminStatsOverview extends BaseWidget
{
    protected static ?int $sort = 1;
    protected ?string $pollingInterval = '30s';

    protected function getStats(): array
    {
        $pendingRequests = SubscriptionRequest::where('status', 'pending')->count();
        $newDemos = DemoRequest::new()->count();

        return [
            Stat::make('Total Users', User::count())
                ->description('Registered accounts')
                ->descriptionIcon('heroicon-m-users')
                ->color('success'),
                
            Stat::make('Active Subscriptions', Subscription::where('status', 'active')->count())
                ->description('Currently active plans')
                ->descriptionIcon('heroicon-m-check-badge')
                ->color('success'),
                
            Stat::make('Pending Sub. Requests', $pendingRequests)
                ->description('Awaiting review')
                ->descriptionIcon('heroicon-m-clock')
                ->color($pendingRequests > 0 ? 'warning' : 'success'),
                
            Stat::make('New Demo Requests', $newDemos)
                ->description('Requires outreach')
                ->descriptionIcon('heroicon-m-megaphone')
                ->color($newDemos > 0 ? 'warning' : 'success'),
        ];
    }
}
