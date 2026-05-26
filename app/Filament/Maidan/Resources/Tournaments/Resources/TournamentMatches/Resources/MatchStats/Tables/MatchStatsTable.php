<?php

namespace App\Filament\Maidan\Resources\Tournaments\Resources\TournamentMatches\Resources\MatchStats\Tables;

use App\Models\MatchStat;
use Filament\Actions\Action;
use Filament\Actions\DeleteAction;
use Filament\Tables\Columns\SelectColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Tables\Table;

class MatchStatsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('tournamentTeam.name')
                    ->label('Team')
                    ->columnSpan(4)
                    ->grow(),
                TextColumn::make('tournamentTeam.short_name')
                    ->label('Short Name')
                    ->alignCenter(),
                TextColumn::make('alive')
                    ->label('Alive')
                    ->alignCenter()
                    ->color('success'),
                TextColumn::make('kills')
                    ->label('Kills')
                    ->alignCenter()
                    ->color('danger'),
                SelectColumn::make('placement')
                    ->sortable()
                    ->disablePlaceholderSelection()
                    ->options(function (MatchStat $record) {
                        $tournamentSetting = $record->tournamentMatch?->tournament?->tournamentSettings->first();

                        if (!$tournamentSetting) {
                            return [];
                        }

                        $options = $tournamentSetting->tournamentSettingPlacementPoints
                            ->pluck('placement', 'placement')
                            ->toArray();
                        // This adds 0 to the beginning of the associative array
                        return [0 => '0'] + $options;
                    })->disableOptionWhen(function ($value, MatchStat $record) {
                        // Never disable '0' (multiple teams can be unranked)
                        if ($value == 0) {
                            return false;
                        }

                        // 2. Check if this placement is already taken by another team in the same match
                        return MatchStat::where('tournament_match_id', $record->tournament_match_id)
                            ->where('id', '!=', $record->id) // Exclude current row
                            ->where('placement', $value)
                            ->exists();
                    })
                    ->alignCenter()
                    ->grow(false)
                    ->default(0)
                    ->afterStateUpdated(function (MatchStat $record) {
                        $record->update(['points' => static::calculatePoints($record)]);
                    }),
                ToggleColumn::make('is_winner')
                    ->beforeStateUpdated(function (MatchStat $record) {
                        MatchStat::where('tournament_match_id', $record->tournament_match_id)
                            ->update(['is_winner' => false]);
                    })
                    ->afterStateUpdated(function ($livewire) {
                        $livewire->dispatch('refreshTournamentMatchForm');
                    }),
                TextColumn::make('points')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->recordActions([
                Action::make('manage_roster')
                    ->label('Manage Roster')
                    ->icon('heroicon-o-users')
                    ->color('warning')
                    ->form(function (MatchStat $record) {
                        return [
                            \Filament\Forms\Components\Select::make('players')
                                ->label('Select Squad Players')
                                ->multiple()
                                ->searchable()
                                ->preload()
                                ->options(function () use ($record) {
                                    return \App\Models\Player::where('tournament_team_id', $record->tournament_team_id)
                                        ->pluck('ign', 'id');
                                })
                                ->required(),
                        ];
                    })
                    ->fillForm(function (MatchStat $record): array {
                        return [
                            'players' => $record->players()->pluck('players.id')->toArray(),
                        ];
                    })
                    ->action(function (MatchStat $record, array $data): void {
                        $playerIds = $data['players'] ?? [];
                        
                        $syncData = [];
                        foreach ($playerIds as $id) {
                            $existing = $record->players()->where('players.id', $id)->first();
                            if ($existing) {
                                $syncData[$id] = [
                                    'kills' => $existing->pivot->kills ?? 0,
                                    'is_alive' => $existing->pivot->is_alive ?? true,
                                ];
                            } else {
                                $syncData[$id] = [
                                    'kills' => 0,
                                    'is_alive' => true,
                                ];
                            }
                        }

                        $record->players()->sync($syncData);
                        $record->recalculateTotals();

                        \Filament\Notifications\Notification::make()
                            ->title('Roster Updated')
                            ->body('Successfully updated squad players for ' . $record->tournamentTeam->name)
                            ->success()
                            ->send();
                    }),
                Action::make('trigger_elimination')
                    ->label('Elim')
                    ->icon('heroicon-o-bell-alert')
                    ->color('danger')
                    ->requiresConfirmation()
                    ->action(function (MatchStat $record) {
                        foreach ($record->players as $player) {
                            $record->players()->updateExistingPivot($player->id, ['is_alive' => false]);
                        }
                        $record->recalculateTotals();

                        $record->load('tournamentTeam');
                        \Illuminate\Support\Facades\Log::info("Manual-triggering elimination for Team: " . $record->tournamentTeam->name);
                        event(new \App\Events\TeamEliminated(
                            $record->tournamentTeam->name,
                            $record->tournamentTeam->logo_image,
                            $record->tournament_match_id
                        ));
                    }),
                DeleteAction::make(),
            ])
            ->toolbarActions([
                // BulkActionGroup::make([
                //     DeleteBulkAction::make(),
                // ]),
            ]);
    }

    public static function calculatePoints(MatchStat $record): int
    {
        // Load the settings through the relationships
        // Suggestion: use optional() or ?. to prevent crashes if settings are missing
        $tournamentSetting = $record->tournamentMatch?->tournament?->tournamentSettings->first();

        if (!$tournamentSetting) {
            return 0;
        }

        $killPoints = ($tournamentSetting->kill_points ?? 0) * $record->kills;

        // Use the correct relationship name from your model (tournamentSettingPlacementPoints)
        // And the correct column name (position vs placement)
        $placementPoints = $tournamentSetting->tournamentSettingPlacementPoints
            ->where('placement', $record->placement)
            ->first()?->points ?? 0;

        return $killPoints + $placementPoints;
    }
}
