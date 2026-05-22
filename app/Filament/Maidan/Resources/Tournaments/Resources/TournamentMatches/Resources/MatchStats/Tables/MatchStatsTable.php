<?php

namespace App\Filament\Maidan\Resources\Tournaments\Resources\TournamentMatches\Resources\MatchStats\Tables;

use App\Models\MatchStat;
use App\Models\TournamentSetting;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\Action;
use Filament\Tables\Columns\SelectColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\TextInputColumn;
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
            ->actions([
                Action::make('manage_roster')
                    ->label('Manage Roster')
                    ->icon('heroicon-o-users')
                    ->color('warning')
                    ->form(function (MatchStat $record) {
                        return [
                            \Filament\Forms\Components\Repeater::make('players_stats')
                                ->label('Squad Players')
                                ->schema([
                                    \Filament\Forms\Components\Hidden::make('id'),
                                    \Filament\Forms\Components\TextInput::make('ign')
                                        ->label('Player IGN')
                                        ->disabled(),
                                    \Filament\Forms\Components\Toggle::make('is_alive')
                                        ->label('Alive')
                                        ->default(true),
                                    \Filament\Forms\Components\TextInput::make('kills')
                                        ->label('Kills')
                                        ->numeric()
                                        ->default(0)
                                        ->minValue(0),
                                ])
                                ->addable(false)
                                ->deletable(false)
                                ->reorderable(false)
                        ];
                    })
                    ->fillForm(function (MatchStat $record): array {
                        $record->load('players');
                        $playersData = [];
                        foreach ($record->players as $player) {
                            $playersData[] = [
                                'id' => $player->id,
                                'ign' => $player->ign,
                                'is_alive' => (bool) $player->pivot->is_alive,
                                'kills' => (int) $player->pivot->kills,
                            ];
                        }
                        return [
                            'players_stats' => $playersData,
                        ];
                    })
                    ->action(function (MatchStat $record, array $data): void {
                        $playersStats = $data['players_stats'] ?? [];
                        foreach ($playersStats as $item) {
                            $playerId = $item['id'] ?? null;
                            if ($playerId) {
                                $record->players()->updateExistingPivot($playerId, [
                                    'is_alive' => (bool)($item['is_alive'] ?? false),
                                    'kills' => (int)($item['kills'] ?? 0),
                                ]);
                            }
                        }
                        $record->recalculateTotals();
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
            ->recordActions([
                Action::make('manage_roster'),
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
