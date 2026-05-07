<?php

namespace App\Filament\Maidan\Resources\Tournaments\Resources\TournamentTeams\Schemas;

use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
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
            ]);
    }
}
