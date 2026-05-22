<?php

namespace App\Filament\Maidan\Resources\Tournaments\Resources\TournamentMatches\Resources\MatchStats\RelationManagers;

use App\Filament\Maidan\Resources\Tournaments\Resources\TournamentMatches\Resources\MatchStats\MatchStatResource;
use Filament\Actions\Action;
use Filament\Actions\CreateAction;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Auth;

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
                        Repeater::make('teams')
                            ->label('Teams & Players')
                            ->schema([
                                Select::make('tournament_team_id')
                                    ->label('Team')
                                    ->options(function () {
                                        $match = $this->getOwnerRecord();
                                        return $match->tournament->tournamentTeams()
                                            ->whereNotIn('id', $match->matchStats()->pluck('tournament_team_id'))
                                            ->pluck('name', 'id');
                                    })
                                    ->searchable()
                                    ->required()
                                    ->live(),
                                Select::make('players')
                                    ->label('Playing Players')
                                    ->multiple()
                                    ->searchable()
                                    ->preload()
                                    ->options(function (Get $get) {
                                        $teamId = $get('tournament_team_id');
                                        if (!$teamId) {
                                            return [];
                                        }
                                        return \App\Models\Player::where('tournament_team_id', $teamId)
                                            ->pluck('ign', 'id');
                                    })
                                    ->required(),
                             ])
                            ->columns(2)
                            ->addActionLabel('Add Team')
                            ->defaultItems(1),
                    ])
                    ->action(function (array $data) {
                        $match = $this->getOwnerRecord();
                        $teams = $data['teams'] ?? [];
                        
                        foreach ($teams as $item) {
                            $teamId = $item['tournament_team_id'];
                            $playerIds = $item['players'] ?? [];
                            
                            if (!$teamId) {
                                continue;
                            }

                            $matchStat = $match->matchStats()->firstOrCreate(
                                ['tournament_team_id' => $teamId],
                                [
                                    'kills' => 0,
                                    'alive' => 4,
                                    'points' => 0,
                                    'placement' => 0,
                                ]
                            );

                            $matchStat->players()->sync($playerIds);
                            $matchStat->recalculateTotals();
                        }

                        \Filament\Notifications\Notification::make()
                            ->title(count($teams) . ' teams populated successfully')
                            ->success()
                            ->send();
                    })
                    ->visible(fn() => $this->getOwnerRecord()->matchStats()->count() < $this->getOwnerRecord()->tournament->tournamentTeams()->count()),
                    Action::make('live_score_update')
                        ->url(fn() => route('screens.activematch', ['user_id' => Auth::id()]))
                        ->openUrlInNewTab()
                        ->visible(fn() => $this->getOwnerRecord()->is_active)
                        ->color('danger'),
                    Action::make('post_match_result')
                        ->url(fn() => route('screens.postmatch', ['user_id' => Auth::id()]))
                        ->openUrlInNewTab()
                        ->visible(fn() => $this->getOwnerRecord()->is_active)
                        ->color('success')
            ]);
    }
}

