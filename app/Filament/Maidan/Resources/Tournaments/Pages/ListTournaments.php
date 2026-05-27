<?php

namespace App\Filament\Maidan\Resources\Tournaments\Pages;

use App\Filament\Maidan\Resources\Tournaments\TournamentResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

use App\Services\SubscriptionService;

class ListTournaments extends ListRecords
{
    protected static string $resource = TournamentResource::class;

    protected function getHeaderActions(): array
    {
        $user = auth()->user();
        $isWithinLimit = SubscriptionService::withinLimit($user, 'max_tournaments', $user->tournaments()->count());

        return [
            CreateAction::make()
                ->disabled(!$isWithinLimit)
                ->tooltip(!$isWithinLimit ? 'Tournament limit reached for your plan.' : null),
        ];
    }
}
