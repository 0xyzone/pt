<?php

namespace App\Filament\Maidan\Resources\Tournaments\Resources\TournamentCasters\RelationManagers;

use Filament\Actions\BulkActionGroup;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Actions\AttachAction;
use Filament\Actions\DetachAction;
use Filament\Actions\DetachBulkAction;
use TinusG\FilamentHoverImageColumn\HoverImageColumn;

class TournamentCastersRelationManager extends RelationManager
{
    protected static string $relationship = 'casters';

    public static function canViewForRecord(\Illuminate\Database\Eloquent\Model $ownerRecord, string $pageClass): bool
    {
        return auth()->check() && \App\Services\SubscriptionService::can(auth()->user(), 'casters_management');
    }

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                // Form components not strictly needed since we attach existing casters,
                // but we keep it empty or return schema.
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('display_name')
            ->columns([
                HoverImageColumn::make('image')
                    ->label('Photo')
                    ->circular()
                    ->imageSize(50)
                    ->disk('public')
                    ->visibility('public'),
                TextColumn::make('display_name')
                    ->label('Display Name')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('display_handle')
                    ->label('Display Handle')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('vdoninja_link')
                    ->label('VDO.Ninja Link')
                    ->searchable(),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                AttachAction::make()
                    ->preloadRecordSelect()
                    ->label('Attach Caster'),
            ])
            ->recordActions([
                DetachAction::make(),
            ])
            ->actions([
                DetachAction::make(),
            ])
            ->bulkActions([
                BulkActionGroup::make([
                    DetachBulkAction::make(),
                ]),
            ])
            ->heading('Casters for Tournament: ' . $this->ownerRecord->name);
    }
}
