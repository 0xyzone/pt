<?php

namespace App\Filament\Maidan\Resources\Tournaments\Resources\TournamentMatches\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\TimePicker;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class TournamentMatchForm
{
    protected static string $relationship = 'tournament_matches';
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Hidden::make('tournament_id')
                    ->default(fn() => request()->route('tournament'))
                    ->required(),
                TextInput::make('name')
                    ->required(),
                // DatePicker::make('match_date')
                // ->native(false),
                // TimePicker::make('match_time')
                // // ->native(false)
                // ->seconds(false)
                // ->displayFormat('h:i A'),
                Select::make('map')
                    ->options([
                        'erangle' => 'Erangle',
                        'miramar' => 'Miramar',
                        'sanhok' => 'Sanhok',
                        'vikendi' => 'Vikendi',
                        'karakin' => 'Karakin',
                        'paramo' => 'Paramo',
                        'haven' => 'Haven',
                    ])
                    ->default('erangle')
                    ->required(),
                Toggle::make('is_active')
                    ->label('Active Match')
                    ->default(false)
                    ->inline(false)
                    ->disabled()
            ])->columns(3);
    }
}
