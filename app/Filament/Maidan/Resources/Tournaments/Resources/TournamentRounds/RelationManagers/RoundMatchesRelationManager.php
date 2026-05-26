<?php

namespace App\Filament\Maidan\Resources\Tournaments\Resources\TournamentRounds\RelationManagers;

use App\Filament\Maidan\Resources\Tournaments\Resources\TournamentMatches\TournamentMatchResource;
use App\Models\Player;
use Filament\Actions\Action;
use Filament\Actions\EditAction;
use Filament\Actions\DeleteAction;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Tables\Table;
use Filament\Notifications\Notification;

class RoundMatchesRelationManager extends RelationManager
{
    protected static string $relationship = 'tournamentMatches';

    protected static ?string $relatedResource = TournamentMatchResource::class;

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('name')
            ->columns([
                TextColumn::make('name')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('map')
                    ->badge()
                    ->color(fn($state) => match (strtolower($state)) {
                        'erangle' => 'primary',
                        'miramar' => 'success',
                        'sanhok' => 'warning',
                        'vikendi' => 'info',
                        'karakin' => 'danger',
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
                    ->disabled(fn ($record) => !$record->matchStats()->where('is_winner', true)->exists())
                    ->afterStateUpdated(function ($record) {
                        broadcast(new \App\Events\TournamentMatchUpdated($record));
                    }),
            ])
            ->actions([
                EditAction::make(),
                DeleteAction::make(),
            ]);
    }
}
