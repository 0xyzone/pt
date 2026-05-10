<?php

namespace App\Filament\Maidan\Resources\Tournaments\Resources\TournamentMatches\Tables;

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
                    }),
                ToggleColumn::make('is_completed')
                    ->label('Completed?'),
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
}
