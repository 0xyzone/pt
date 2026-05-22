<?php

namespace App\Filament\Maidan\Resources\Tournaments\Resources\TournamentRounds\Schemas;

use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class TournamentRoundForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Hidden::make('tournament_id')
                    ->default(fn() => \Illuminate\Support\Facades\Request::route('tournament'))
                    ->required(),
                TextInput::make('name')
                    ->label('Round Name')
                    ->placeholder('e.g., Qualifier Round')
                    ->required()
                    ->columnSpanFull(),
                Repeater::make('maps')
                    ->label('Maps in this Round')
                    ->schema([
                        Select::make('map')
                            ->options([
                                'erangle' => 'Erangel (PUBG)',
                                'miramar' => 'Miramar (PUBG)',
                                'sanhok' => 'Sanhok (PUBG)',
                                'rondo' => 'Rondo (PUBG)',
                                'vikendi' => 'Vikendi (PUBG)',
                                'taego' => 'Taego (PUBG)',
                                'deston' => 'Deston (PUBG)',
                                'karakin' => 'Karakin (PUBG)',
                                'paramo' => 'Paramo (PUBG)',
                                'haven' => 'Haven (PUBG)',
                            ])
                            ->required()
                            ->default('erangle'),
                    ])
                    ->grid(2)
                    ->defaultItems(1)
                    ->addActionLabel('Add Map')
                    ->columnSpanFull()
            ]);
    }
}
