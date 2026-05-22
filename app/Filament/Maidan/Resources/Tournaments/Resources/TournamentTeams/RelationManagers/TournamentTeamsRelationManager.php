<?php

namespace App\Filament\Maidan\Resources\Tournaments\Resources\TournamentTeams\RelationManagers;

use Filament\Actions\Action;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\CreateAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\DissociateAction;
use Filament\Actions\DissociateBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Auth;

class TournamentTeamsRelationManager extends RelationManager
{
    protected static string $relationship = 'tournamentTeams';

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->columnSpanFull()
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

                Section::make('Team Players')
                    ->description('Manage players on this team and their in-game details.')
                    ->icon('heroicon-o-users')
                    ->columnSpanFull()
                    ->collapsible()
                    ->schema([
                        Repeater::make('players')
                            ->relationship('players')
                            ->label(false) // Hide label of repeater as the section header serves as the label
                            ->schema([
                                TextInput::make('name')
                                    ->label('Real Name')
                                    ->maxLength(255)
                                    ->placeholder('e.g., John Doe'),
                                TextInput::make('ign')
                                    ->label('In-Game Name (IGN)')
                                    ->required()
                                    ->maxLength(255)
                                    ->placeholder('e.g., Shroud'),
                                TextInput::make('in_game_id')
                                    ->label('In-Game ID (UID)')
                                    ->maxLength(255)
                                    ->placeholder('e.g., 5123456789'),
                                Select::make('role')
                                    ->label('Role')
                                    ->options([
                                        'player' => 'Regular Player',
                                        'igl' => 'IGL (In-Game Leader)',
                                        'substitute' => 'Substitute',
                                        'manager' => 'Manager',
                                    ])
                                    ->default('player')
                                    ->required()
                                    ->native(false),
                            ])
                            ->columns(2)
                            ->grid(2)
                            ->defaultItems(0)
                            ->addActionLabel('Add Player')
                    ]),
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
                    ->url(fn($record) => 'https://wa.me/' . preg_replace('/\D/', '', $record->whatsapp_number))
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
                Action::make('associateExistingTeam')
                    ->label('Associate Existing')
                    ->icon('heroicon-o-link')
                    ->color('info')
                    ->form([
                        Select::make('team_ids')
                            ->label('Select Teams')
                            ->options(function () {
                                return \App\Models\TournamentTeam::query()
                                    ->whereHas('tournament', fn($q) => $q->where('user_id', Auth::id()))
                                    ->where('tournament_id', '!=', $this->getOwnerRecord()->id)
                                    ->pluck('name', 'id');
                            })
                            ->multiple()
                            ->searchable()
                            ->required(),
                    ])
                    ->action(function (array $data) {
                        $teamIds = $data['team_ids'] ?? [];

                        foreach ($teamIds as $teamId) {
                            $team = \App\Models\TournamentTeam::find($teamId);
                            if ($team) {
                                $team->tournament_id = $this->getOwnerRecord()->id;
                                $team->save();
                            }
                        }

                        if (count($teamIds) > 0) {
                            \Filament\Notifications\Notification::make()
                                ->title(count($teamIds) . ' teams associated successfully')
                                ->success()
                                ->send();
                        }
                    }),
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
