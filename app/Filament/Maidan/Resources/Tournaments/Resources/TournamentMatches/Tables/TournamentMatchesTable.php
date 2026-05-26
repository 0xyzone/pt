<?php

namespace App\Filament\Maidan\Resources\Tournaments\Resources\TournamentMatches\Tables;

use Filament\Actions\Action;
use Filament\Actions\EditAction;
use Filament\Forms\Components\Select;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Tables\Table;

class TournamentMatchesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('tournament.name')
                    ->searchable(),
                TextColumn::make('name')
                    ->searchable(),
                TextColumn::make('tournamentRound.name')
                    ->label('Round')
                    ->searchable()
                    ->badge()
                    ->color('info'),
                // TextColumn::make('match_date')
                //     ->date()
                //     ->sortable(),
                // TextColumn::make('match_time')
                //     ->time()
                //     ->sortable(),
                TextColumn::make('map')
                    ->searchable()
                    ->badge()
                    ->color(fn($state) => match ($state) {
                        'Erangel' => 'primary',
                        'Miramar' => 'success',
                        'Sanhok' => 'warning',
                        'Vikendi' => 'info',
                        'Karakin' => 'danger',
                        default => null,
                    })
                    ->formatStateUsing(fn($state) => ucfirst($state)),
                ToggleColumn::make('is_active')
                    ->label('Activate?')
                    ->beforeStateUpdated(function ($record) {
                        $record->tournament->tournamentMatches()->update(['is_active' => false]);
                        $record->update(['is_active' => true]);
                        broadcast(new \App\Events\TournamentMatchUpdated($record));
                    }),
                // ToggleColumn::make('is_completed')
                //     ->label('Completed')
                //     ->disabled(fn ($record) => !$record->matchStats()->where('is_winner', true)->exists())
                //     ->afterStateUpdated(function ($record) {
                //         broadcast(new \App\Events\TournamentMatchUpdated($record));
                //     }),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->defaultGroup('tournamentRound.name')
            ->recordActions([
                // Action::make('complete')
                //     ->label(fn($record) => $record->is_completed ? 'Completed' : 'Mark Complete')
                //     ->icon(fn($record) => $record->is_completed ? 'heroicon-s-check-circle' : 'heroicon-o-check-circle')
                //     ->color(fn($record) => $record->is_completed ? 'success' : 'gray')
                //     ->disabled(fn ($record) => !$record->is_completed && !$record->matchStats()->where('is_winner', true)->exists())
                //     ->action(function ($record) {
                //         $record->update(['is_completed' => !$record->is_completed]);
                //         broadcast(new \App\Events\TournamentMatchUpdated($record));
                //     })
                //     ->requiresConfirmation(function ($record) {
                //         return !$record->is_completed && $record->matchStats->where('placement', 1)->count() === 0;
                //     })
                //     ->modalHeading('Winner not assigned')
                //     ->modalDescription('You are marking this match as completed, but no team has been assigned 1st place (Placement 1). Continue anyway?')
                //     ->modalSubmitActionLabel('Yes, mark as completed'),
                Action::make('clone_teams')
                    ->label('Clone Teams')
                    ->icon('heroicon-o-document-duplicate')
                    ->color('warning')
                    ->visible(fn($record) => $record->matchStats()->exists())
                    ->form(fn($record) => [
                        Select::make('target_match_id')
                            ->label('Select Target Match')
                            ->options(function () use ($record) {
                                return \App\Models\TournamentMatch::where('tournament_id', $record->tournament_id)
                                    ->where('id', '!=', $record->id)
                                    ->orderBy('id')
                                    ->pluck('name', 'id');
                            })
                            ->required()
                            ->searchable(),
                    ])
                    ->action(function ($record, array $data) {
                        $targetMatch = \App\Models\TournamentMatch::find($data['target_match_id']);
                        if (!$targetMatch) {
                            \Filament\Notifications\Notification::make()
                                ->title('Target match not found')
                                ->danger()
                                ->send();
                            return;
                        }

                        $sourceMatchStats = $record->matchStats()->with('players')->get();
                        $clonedCount = 0;

                        foreach ($sourceMatchStats as $sourceMatchStat) {
                            $targetMatchStat = $targetMatch->matchStats()->updateOrCreate(
                                ['tournament_team_id' => $sourceMatchStat->tournament_team_id],
                                [
                                    'kills' => 0,
                                    'placement' => 0,
                                    'is_winner' => false,
                                    'points' => 0,
                                ]
                            );

                            $syncData = [];
                            foreach ($sourceMatchStat->players as $player) {
                                $syncData[$player->id] = [
                                    'kills' => 0,
                                    'is_alive' => true,
                                ];
                            }

                            $targetMatchStat->players()->sync($syncData);
                            $targetMatchStat->recalculateTotals();
                            $clonedCount++;
                        }

                        // Broadcast update event for target match
                        broadcast(new \App\Events\TournamentMatchUpdated($targetMatch));

                        \Filament\Notifications\Notification::make()
                            ->title('Teams Cloned Successfully')
                            ->body("Successfully cloned {$clonedCount} teams and their players from \"{$record->name}\" to \"{$targetMatch->name}\" with reset stats.")
                            ->success()
                            ->send();
                    }),
                EditAction::make(),
            ])
            ->filters([
                //
            ])
            ->toolbarActions([
                // BulkActionGroup::make([
                //     DeleteBulkAction::make(),
                // ]),
            ]);
    }
}
