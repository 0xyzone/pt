<?php

namespace App\Filament\Maidan\Resources\Tournaments\Resources\TournamentMatches\Resources\MatchStats\RelationManagers;

use App\Filament\Maidan\Resources\Tournaments\Resources\TournamentMatches\Resources\MatchStats\MatchStatResource;
use Filament\Actions\Action;
use Filament\Actions\CreateAction;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables\Table;

class MatchStatsRelationManager extends RelationManager
{
    protected static string $relationship = 'matchStats';

    protected static ?string $relatedResource = MatchStatResource::class;

    public function table(Table $table): Table
    {
        return $table
            ->headerActions([
                // CreateAction::make(),
                Action::make('populate')
                    ->action(function () {
                        $teams = $this->ownerRecord->tournament->tournamentTeams;
                        foreach ($teams as $team) {
                            $this->ownerRecord->matchStats()->create([
                                'tournament_team_id' => $team->id,
                                'kills' => 0,
                                'alive' => 4,
                            ]);
                        }
                    })
                    ->visible(fn() => $this->ownerRecord->matchStats()->count() === 0),
                    Action::make('view_screen')
                    ->url(fn() => route('screens.activematch', ['user_id' => auth()->id()]))
                    ->openUrlInNewTab()
                    ->visible(fn() => $this->ownerRecord->is_active)
            ]);
    }
}
