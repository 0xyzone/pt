<?php

namespace App\Filament\Maidan\Resources\Tournaments\Resources\TournamentTeams\Schemas;

use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class TournamentTeamForm
{
    public static function configure(Schema $schema): Schema
    {
        $tournamentId = request()->route('tournament');
        return $schema
            ->components([
                Hidden::make('tournament_id')
                    ->default(fn() => $tournamentId), // Set default to the current tournament ID from the route
                TextInput::make('name')
                    ->required(),
                    TextInput::make('contact_number'),
                    TextInput::make('contact_email')
                    ->email(),
                    TextInput::make('whatsapp_number'),
                    TextInput::make('short_name'),
                    FileUpload::make('logo_image')
                        ->label('Team Logo')
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
                        ->collapsible()
                        ->columnSpanFull()
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
}
