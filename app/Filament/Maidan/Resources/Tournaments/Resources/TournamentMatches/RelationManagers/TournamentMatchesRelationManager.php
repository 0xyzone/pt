<?php

namespace App\Filament\Maidan\Resources\Tournaments\Resources\TournamentMatches\RelationManagers;

use App\Filament\Maidan\Resources\Tournaments\Resources\TournamentMatches\TournamentMatchResource;
use Filament\Actions\CreateAction;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables\Table;

class TournamentMatchesRelationManager extends RelationManager
{
    protected static string $relationship = 'tournamentMatches';

    protected static ?string $relatedResource = TournamentMatchResource::class;

    public function table(Table $table): Table
    {
        return $table
            ->headerActions([
                CreateAction::make()
                    ->disabled(fn () => !\App\Services\SubscriptionService::withinLimit(auth()->user(), 'max_matches', $this->getOwnerRecord()->tournamentMatches()->count()))
                    ->tooltip(fn () => !\App\Services\SubscriptionService::withinLimit(auth()->user(), 'max_matches', $this->getOwnerRecord()->tournamentMatches()->count()) ? 'Match limit reached for this tournament.' : null)
                    ->before(function (\Filament\Actions\CreateAction $action) {
                        if (!\App\Services\SubscriptionService::withinLimit(auth()->user(), 'max_matches', $this->getOwnerRecord()->tournamentMatches()->count())) {
                            \Filament\Notifications\Notification::make()
                                ->title('Match limit reached')
                                ->body('Upgrade your plan to add more matches.')
                                ->danger()
                                ->send();
                            $action->halt();
                        }
                    }),
            ]);
    }
}
