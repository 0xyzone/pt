<?php

namespace App\Filament\Maidan\Resources\Tournaments\Resources\TournamentMatches\Tables;

use Filament\Actions\Action;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
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
                ToggleColumn::make('is_completed')
                    ->label('Completed')
                    ->afterStateUpdated(function ($record) {
                        broadcast(new \App\Events\TournamentMatchUpdated($record));
                    }),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->actions([
                Action::make('complete')
                    ->label(fn($record) => $record->is_completed ? 'Completed' : 'Mark Complete')
                    ->icon(fn($record) => $record->is_completed ? 'heroicon-s-check-circle' : 'heroicon-o-check-circle')
                    ->color(fn($record) => $record->is_completed ? 'success' : 'gray')
                    ->action(function ($record) {
                        $record->update(['is_completed' => !$record->is_completed]);
                        broadcast(new \App\Events\TournamentMatchUpdated($record));
                    })
                    ->requiresConfirmation(function ($record) {
                        return !$record->is_completed && $record->matchStats->where('placement', 1)->count() === 0;
                    })
                    ->modalHeading('Winner not assigned')
                    ->modalDescription('You are marking this match as completed, but no team has been assigned 1st place (Placement 1). Continue anyway?')
                    ->modalSubmitActionLabel('Yes, mark as completed'),
                EditAction::make(),
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
}
