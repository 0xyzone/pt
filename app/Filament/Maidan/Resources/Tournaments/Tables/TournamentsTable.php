<?php

namespace App\Filament\Maidan\Resources\Tournaments\Tables;

use App\Filament\Maidan\Resources\Tournaments\TournamentResource;
use App\Models\Tournament;
use Filament\Actions\Action;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\SelectColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Tables\Table;

class TournamentsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                ImageColumn::make('logo_image')
                ->disk('public'),
                ImageColumn::make('banner_image')
                ->disk('public'),
                TextColumn::make('name')
                    ->searchable(),
                SelectColumn::make('status')
                    ->options([
                        'upcoming' => 'Upcoming',
                        'ongoing' => 'Ongoing',
                        'completed' => 'Completed',
                    ]),
                    ToggleColumn::make('is_active')
                    ->label('Activate?')
                    ->beforeStateUpdated(function (Tournament $record) {
                        Tournament::where('user_id', auth()->id())->where('id', '!=', $record->id)->update(['is_active' => false]);
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
                // ViewAction::make(),
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
