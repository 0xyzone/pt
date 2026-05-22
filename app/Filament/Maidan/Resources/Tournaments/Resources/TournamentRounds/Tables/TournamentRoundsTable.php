<?php

namespace App\Filament\Maidan\Resources\Tournaments\Resources\TournamentRounds\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class TournamentRoundsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('maps')
                    ->label('Maps')
                    ->formatStateUsing(function ($state) {
                        if (is_string($state)) {
                            $state = json_decode($state, true);
                        }
                        if (!is_array($state)) {
                            return '';
                        }
                        return implode(', ', array_filter(array_map(function ($item) {
                            if (is_array($item)) {
                                $mapName = $item['map'] ?? '';
                            } elseif (is_object($item)) {
                                $mapName = $item->map ?? '';
                            } else {
                                $mapName = $item;
                            }
                            return $mapName ? ucfirst($mapName) : null;
                        }, $state)));
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
