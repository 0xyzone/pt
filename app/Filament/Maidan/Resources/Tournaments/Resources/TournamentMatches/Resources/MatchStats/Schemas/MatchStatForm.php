<?php

namespace App\Filament\Maidan\Resources\Tournaments\Resources\TournamentMatches\Resources\MatchStats\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Schema;

class MatchStatForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('tournament_team_id')
                    ->relationship('tournamentTeam', 'name')
                    ->required()
                    ->live(),
                Select::make('players')
                    ->label('Playing Players')
                    ->multiple()
                    ->relationship('players', 'ign')
                    ->searchable()
                    ->preload()
                    ->options(function (Get $get) {
                        $teamId = $get('tournament_team_id');
                        if (!$teamId) {
                            return [];
                        }
                        return \App\Models\Player::where('tournament_team_id', $teamId)
                            ->pluck('ign', 'id');
                    }),
            ]);
    }
}
