<?php

namespace App\Filament\Maidan\Resources\Tournaments\Resources\TournamentMatches\Pages;

use App\Filament\Maidan\Resources\Tournaments\Resources\TournamentMatches\TournamentMatchResource;
use App\Filament\Maidan\Resources\Tournaments\TournamentResource;
use Filament\Resources\Pages\CreateRecord;

class CreateTournamentMatch extends CreateRecord
{
    protected static string $resource = TournamentMatchResource::class;

    protected function beforeCreate(): void
    {
        $tournament = $this->getParentRecord();
        $user = auth()->user();
        $currentCount = $tournament ? $tournament->tournamentMatches()->count() : 0;
        if (!\App\Services\SubscriptionService::withinLimit($user, 'max_matches', $currentCount)) {
            \Filament\Notifications\Notification::make()
                ->title('Match limit reached')
                ->body('Upgrade your plan to add more matches to this tournament.')
                ->danger()
                ->send();

            $this->halt();
        }
    }

    public function getSubNavigation(): array
    {
        return TournamentResource::getRecordSubNavigation($this);
    }

    public function getSubNavigationParameters(): array
    {
        return [
            'record' => $this->getParentRecord(),
        ];
    }
}
