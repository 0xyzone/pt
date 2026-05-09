<?php

namespace App\Filament\Maidan\Resources\Tournaments\Resources\TournamentTeams\RelationManagers;

use Filament\Actions\AssociateAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\CreateAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\DissociateAction;
use Filament\Actions\DissociateBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\TextInput;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class TournamentTeamsRelationManager extends RelationManager
{
    protected static string $relationship = 'tournamentTeams';

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->required()
                    ->maxLength(255),
                TextInput::make('short_name')
                    ->maxLength(255),
                TextInput::make('contact_number')
                    ->maxLength(255),
                TextInput::make('contact_email')
                    ->email()
                    ->maxLength(255),
                TextInput::make('whatsapp_number')
                    ->maxLength(255),
                    FileUpload::make('logo_image')
                    ->image()
                    ->directory('tournament-team-logos')
                    ->visibility('public')
                    ->disk('public')
                    ->imageAspectRatio('1:1')
                    ->imageEditor()
                    ->imageEditorAspectRatioOptions(['1:1'])
                    ->automaticallyResizeImagesToWidth(500)
                    ->automaticallyResizeImagesToHeight(500)
                    ->automaticallyOpenImageEditorForAspectRatio(),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('name')
            ->columns([
                ImageColumn::make('logo_image')
                ->label('Logo')
                ->circular()
                ->imageSize(50)
                ->disk('public')
                ->visibility('public'),
                TextColumn::make('name')
                    ->searchable(),
                TextColumn::make('short_name')
                    ->searchable(),
                TextColumn::make('contact_number')
                    ->searchable(),
                TextColumn::make('contact_email')
                    ->searchable(),
                TextColumn::make('whatsapp_number')
                    ->searchable()
                    ->url(fn ($record) => 'https://wa.me/' . preg_replace('/\D/', '', $record->whatsapp_number))
                    ->openUrlInNewTab()
                    ->label('WhatsApp')
                    ->icon('heroicon-o-chat-bubble-oval-left'),
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
            ->headerActions([
                CreateAction::make()
                ->keyBindings(['ctrl+n'])
                ->label('Add Team (Ctrl + N)'),
                // AssociateAction::make(),
            ])
            ->recordActions([
                EditAction::make(),
                DissociateAction::make(),
                DeleteAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DissociateBulkAction::make(),
                    DeleteBulkAction::make(),
                ]),
            ])
            ->heading('Manage Teams for Tournament: ' . $this->ownerRecord->name);
    }
}
