<?php

namespace App\Filament\Maidan\Resources\Tournaments\Resources\TournamentSponsors\RelationManagers;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\CreateAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\TextInput;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\TextInputColumn;
use Filament\Tables\Table;

class TournamentSponsorsRelationManager extends RelationManager
{
    protected static string $relationship = 'tournamentSponsors';

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->required()
                    ->maxLength(255),
                TextInput::make('sort_order')
                    ->numeric()
                    ->default(0),
                FileUpload::make('logo_image')
                    ->label('Sponsor Logo')
                    ->image()
                    ->directory('tournament-sponsor-logos')
                    ->visibility('public')
                    ->disk('public')
                    ->imageEditor()
                    ->imageEditorAspectRatioOptions(['1:1', '16:9', '4:3'])
                    ->automaticallyResizeImagesToWidth(500)
                    ->automaticallyResizeImagesToHeight(500),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('name')
            ->reorderable('sort_order')
            ->columns([
                ImageColumn::make('logo_image')
                    ->label('Logo')
                    ->imageSize(60)
                    ->disk('public')
                    ->visibility('public'),
                TextColumn::make('name')
                    ->searchable(),
                TextColumn::make('sort_order')
                    ->label('Order')
                    ->sortable(),
            ])
            ->defaultSort('sort_order')
            ->filters([
                //
            ])
            ->headerActions([
                CreateAction::make()
                    ->label('Add Sponsor'),
            ])
            ->recordActions([
                EditAction::make(),
                DeleteAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ])
            ->heading('Sponsors for: ' . $this->ownerRecord->name);
    }
}
