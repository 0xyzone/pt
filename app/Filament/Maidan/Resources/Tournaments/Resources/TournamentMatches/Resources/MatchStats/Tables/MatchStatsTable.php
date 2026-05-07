<?php

namespace App\Filament\Maidan\Resources\Tournaments\Resources\TournamentMatches\Resources\MatchStats\Tables;

use App\Models\MatchStat;
use App\Models\TournamentSetting;
use Filament\Tables\Actions\Action;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
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
                TextColumn::make('decrease_alive')
                    ->label('')
                    ->getStateUsing(fn() => '-')
                    ->disabled(fn(MatchStat $record) => $record->alive <= 0)
                    ->action(function (MatchStat $record) {
                        if ($record->alive > 0) {
                            $record->decrement('alive');
                        } else {
                            $record->update(['alive' => 0]);
                        }
                    })
                    ->alignCenter(),
                TextColumn::make('alive')
                    ->alignCenter()
                    ->color('success'),
                TextColumn::make('increase_alive')
                    ->label('')
                    ->getStateUsing(fn() => '+')
                    ->action(function (MatchStat $record) {
                        if ($record->alive < 4) {
                            $record->increment('alive');
                        } else {
                            $record->update(['alive' => 4]);
                        };
                    })
                    ->alignCenter(),
                TextColumn::make('decrease_kills')
                    ->label('')
                    ->getStateUsing(fn() => '-')
                    ->disabled(fn(MatchStat $record) => $record->kills <= 0)
                    ->action(function (MatchStat $record) {
                        if ($record->kills > 0) {
                            $record->decrement('kills');
                        }

                        // Refresh the record to get the new 'kills' value before calculating
                        $record->refresh();
                        $record->update([
                            'points' => static::calculatePoints($record)
                        ]);
                    })
                    ->alignCenter(),
                TextColumn::make('kills')
                    ->alignCenter()
                    ->color('danger'),
                TextColumn::make('increase_kills')
                    ->label('')
                    ->getStateUsing(fn() => '+')
                    ->action(function (MatchStat $record) {
                        if ($record->kills < 100) { // Assuming a maximum of 100 kills
                            $record->increment('kills');
                        }

                        // Refresh the record to get the new 'kills' value before calculating
                        $record->refresh();
                        $record->update([
                            'points' => static::calculatePoints($record)
                        ]);
                    })
                    ->alignCenter(),
                SelectColumn::make('placement')
                    ->disablePlaceholderSelection()
                    ->options(function (MatchStat $record) {
                        $tournamentSetting = $record->tournamentMatch?->tournament?->tournamentSettings->first();

                        if (!$tournamentSetting) {
                            return [];
                        }

                        return $tournamentSetting->tournamentSettingPlacementPoints
                            ->pluck('placement', 'placement')
                            ->toArray();
                    })
                    ->alignCenter()
                    ->grow(false)
                    ->afterStateUpdated(function (MatchStat $record) {
                        $record->update(['points' => static::calculatePoints($record)]);
                    }),
                ToggleColumn::make('is_winner')
                    ->beforeStateUpdated(function (MatchStat $record) {
                        MatchStat::where('tournament_match_id', $record->tournament_match_id)
                            ->update(['is_winner' => false]);
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
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
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
