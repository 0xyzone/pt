<?php

namespace App\Filament\Resources\Subscriptions\Pages;

use App\Filament\Resources\Subscriptions\SubscriptionResource;
use App\Models\Subscription;
use Filament\Resources\Pages\ListRecords;
use Filament\Schemas\Components\Tabs\Tab;
use Illuminate\Database\Eloquent\Builder;

class ListSubscriptions extends ListRecords
{
    protected static string $resource = SubscriptionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            \Filament\Actions\CreateAction::make()->label('Assign Plan to User'),
        ];
    }

    public function getTabs(): array
    {
        return [
            'all' => Tab::make('All')->badge(Subscription::count()),

            'active' => Tab::make('Active')
                ->badge(Subscription::active()->count())
                ->badgeColor('success')
                ->modifyQueryUsing(fn (Builder $q) => $q->active()),

            'expiring' => Tab::make('Expiring Soon')
                ->badge(Subscription::active()->whereNotNull('ends_at')->where('ends_at', '<=', now()->addDays(30))->count())
                ->badgeColor('warning')
                ->modifyQueryUsing(fn (Builder $q) =>
                    $q->active()->whereNotNull('ends_at')->where('ends_at', '<=', now()->addDays(30))
                ),

            'expired' => Tab::make('Expired / Cancelled')
                ->modifyQueryUsing(fn (Builder $q) =>
                    $q->where('status', 'expired')->orWhere('status', 'cancelled')
                ),
        ];
    }
}
