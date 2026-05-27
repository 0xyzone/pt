<?php

namespace App\Filament\Maidan\Resources\Tournaments\Pages;

use App\Filament\Maidan\Resources\Tournaments\TournamentResource;
use Filament\Resources\Pages\CreateRecord;

use App\Services\SubscriptionService;
use Filament\Notifications\Notification;

class CreateTournament extends CreateRecord
{
    protected static string $resource = TournamentResource::class;

    protected function beforeCreate(): void
    {
        $user = auth()->user();
        if (!SubscriptionService::withinLimit($user, 'max_tournaments', $user->tournaments()->count())) {
            Notification::make()
                ->title('Tournament limit reached')
                ->body('Upgrade your plan to create more tournaments.')
                ->danger()
                ->send();

            $this->halt();
        }
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
