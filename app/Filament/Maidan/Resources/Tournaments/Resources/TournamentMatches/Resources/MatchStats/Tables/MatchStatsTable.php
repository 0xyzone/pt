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
                TextColumn::make('decrease_alive')
                    ->label('')
                    ->getStateUsing(fn() => '-')
                    ->disabled(fn(MatchStat $record) => $record->alive <= 0)
                    ->action(function (MatchStat $record) {
                        if ($record->alive > 0) {
                            $record->update(['alive' => $record->alive - 1]);
                        } else {
                            $record->update(['alive' => 0]);
                        }
                    })
                    ->alignCenter()
                    ->badge()
                    ->color('danger'),
                TextColumn::make('alive')
                    ->alignCenter()
                    ->color('success'),
                TextColumn::make('increase_alive')
                    ->label('')
                    ->getStateUsing(fn() => '+')
                    ->action(function (MatchStat $record) {
                        if ($record->alive < 4) {
                            $record->update(['alive' => $record->alive + 1]);
                        } else {
                            $record->update(['alive' => 4]);
                        };
                    })
                    ->alignCenter()
                    ->badge()
                    ->color('success'),
                TextColumn::make('decrease_kills')
                    ->label('')
                    ->getStateUsing(fn() => '-')
                    ->disabled(fn(MatchStat $record) => $record->kills <= 0)
                    ->action(function (MatchStat $record) {
                        if ($record->kills > 0) {
                            $record->update(['kills' => $record->kills - 1]);
                        }

                        $record->update([
                            'points' => static::calculatePoints($record)
                        ]);
                    })
                    ->alignCenter()
                    ->badge()
                    ->color('danger'),
                TextColumn::make('kills')
                    ->alignCenter()
                    ->color('danger'),
                TextColumn::make('increase_kills')
                    ->label('')
                    ->badge()
                    ->color('success')
                    ->getStateUsing(fn() => '+')
                    ->action(function (MatchStat $record) {
                        if ($record->kills < 100) { // Assuming a maximum of 100 kills
                            $record->update(['kills' => $record->kills + 1]);
                        }

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
                Action::make('trigger_elimination')
                    ->label('Elim')
                    ->icon('heroicon-o-bell-alert')
                    ->color('danger')
                    ->action(function (MatchStat $record) {
                        broadcast(new \App\Events\TeamEliminated(
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
