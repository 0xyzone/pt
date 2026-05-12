<?php

namespace App\Filament\Maidan\Resources\Tournaments\Resources\TournamentMatches\Resources\MatchStats\RelationManagers;

use App\Filament\Maidan\Resources\Tournaments\Resources\TournamentMatches\Resources\MatchStats\MatchStatResource;
use Filament\Actions\Action;
use Filament\Actions\CreateAction;
use Filament\Forms\Components\Select;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Components\Actions;
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
                    ->label('Populate Teams')
                    ->icon('heroicon-o-users')
                    ->color('info')
                    ->form([
                        Select::make('team_ids')
                            ->label('Select Teams')
                            ->options(function () {
                                $match = $this->getOwnerRecord();
                                return $match->tournament->tournamentTeams()
                                    ->whereNotIn('id', $match->matchStats()->pluck('tournament_team_id'))
                                    ->pluck('name', 'id');
                            })
                            ->multiple()
                            ->searchable()
                            ->required()
                            ->hint('Only teams not already in the match are shown.'),
                    ])
                    ->action(function (array $data) {
                        $match = $this->getOwnerRecord();
                        $teamIds = $data['team_ids'] ?? [];
                        
                        foreach ($teamIds as $teamId) {
                            $match->matchStats()->firstOrCreate(
                                ['tournament_team_id' => $teamId],
                                [
                                    'kills' => 0,
                                    'alive' => 4,
                                    'points' => 0,
                                    'placement' => 0,
                                ]
                            );
                        }

                        \Filament\Notifications\Notification::make()
                            ->title(count($teamIds) . ' teams populated successfully')
                            ->success()
                            ->send();
                    })
                    ->visible(fn() => $this->getOwnerRecord()->matchStats()->count() < $this->getOwnerRecord()->tournament->tournamentTeams()->count()),
                    Action::make('live_score_update')
                        ->url(fn() => route('screens.activematch', ['user_id' => auth()->id()]))
                        ->openUrlInNewTab()
                        ->visible(fn() => $this->ownerRecord->is_active)
                        ->color('danger'),
                    Action::make('post_match_result')
                        ->url(fn() => route('screens.postmatch', ['user_id' => auth()->id()]))
                        ->openUrlInNewTab()
                        ->visible(fn() => $this->ownerRecord->is_active)
                        ->color('success')
            ]);
    }
}
