<?php

namespace App\Filament\Resources\Users\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class UsersTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->searchable(),
                TextColumn::make('email')
                    ->label('Email address')
                    ->searchable(),
                TextColumn::make('latestSubscription.plan.name')
                    ->label('Plan')
                    ->badge()
                    ->color('info')
                    ->placeholder('No Plan'),
                TextColumn::make('latestSubscription.status')
                    ->label('Status')
                    ->badge()
                    ->color(fn ($state) => match($state) {
                        'active' => 'success',
                        'expired', 'cancelled' => 'danger',
                        default => 'gray',
                    })
                    ->placeholder('Inactive'),
                TextColumn::make('latestSubscription.ends_at')
                    ->label('Expires At')
                    ->dateTime('d M Y')
                    ->placeholder('No Expiry'),
                TextColumn::make('tournaments_count')
                    ->counts('tournaments')
                    ->label('Tournaments')
                    ->badge()
                    ->color('gray'),
                TextColumn::make('email_verified_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
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
