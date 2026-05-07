<?php

namespace App\Filament\Maidan\Resources\Tournaments\Tables;

use App\Filament\Maidan\Resources\Tournaments\TournamentResource;
use Filament\Actions\Action;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Select;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class TournamentsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->searchable(),
                TextColumn::make('start_date')
                    ->date()
                    ->sortable(),
                TextColumn::make('end_date')
                    ->date()
                    ->sortable(),
                TextColumn::make('location')
                    ->searchable(),
                TextColumn::make('status')
                    ->badge(),
                ImageColumn::make('logo_image'),
                ImageColumn::make('banner_image'),
                TextColumn::make('contact_email')
                    ->searchable(),
                TextColumn::make('discord_link')
                    ->searchable(),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->modifyQueryUsing(fn($query) => $query->where('user_id', auth()->id()))
            ->emptyStateHeading('No tournaments organized yet.')
            ->emptyStateDescription('Get started by creating a new tournament.')
            ->emptyStateIcon(icon: 'heroicon-o-trophy')
            ->emptyStateActions([
                Action::make('create')
                    ->label('Create Tournament')
                    ->url(fn(): string => TournamentResource::getUrl('create'))
                    ->button(),
            ])
            ->filters([
                //
            ])
            ->recordActions([
                ViewAction::make(),
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
