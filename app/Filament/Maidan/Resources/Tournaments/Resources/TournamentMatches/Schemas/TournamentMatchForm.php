<?php

namespace App\Filament\Maidan\Resources\Tournaments\Resources\TournamentMatches\Schemas;

use App\Models\TournamentMatch;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\TimePicker;
use Filament\Notifications\Notification;
use Filament\Schemas\Schema;

class TournamentMatchForm
{
    protected static string $relationship = 'tournament_matches';
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Hidden::make('tournament_id')
                    ->default(fn() => \Illuminate\Support\Facades\Request::route('tournament'))
                    ->required(),
                Select::make('tournament_round_id')
                    ->disabled()
                    ->label('Round')
                    ->options(function ($record) {
                        $tournamentId = \Illuminate\Support\Facades\Request::route('tournament');
                        if (!$tournamentId && $record) {
                            $tournamentId = $record->tournament_id;
                        }
                        if (!$tournamentId) {
                            return [];
                        }
                        return \App\Models\TournamentRound::where('tournament_id', $tournamentId)->pluck('name', 'id');
                    })
                    ->nullable()
                    ->searchable(),
                TextInput::make('name')
                    ->required()
                    ->live(onBlur: true)
                    ->columnSpan(3)
                    ->afterStateUpdated(function (TournamentMatch $record, $state) {
                        $record->update(['name' => $state]);
                        $record->save();
                        Notification::make()
                            ->title('Name updated successfully')
                            ->success()
                            ->send();
                    }),
                DatePicker::make('match_date')
                    ->native(false)
                    ->default(now())
                    ->live()
                    ->afterStateUpdated(function (TournamentMatch $record, $state) {
                        $record->update(['match_date' => $state]);
                        $record->save();
                        Notification::make()
                            ->title('Date updated successfully')
                            ->success()
                            ->send();
                    }),
                TimePicker::make('match_time')
                    // ->native(false)
                    ->seconds(false)
                    ->displayFormat('h:i A')
                    ->default(now())
                    ->live()
                    ->afterStateUpdated(function (TournamentMatch $record, $state) {
                        $record->update(['match_time' => $state]);
                        $record->save();
                        Notification::make()
                            ->title('Time updated successfully')
                            ->success()
                            ->send();
                    }),
                Select::make('map')
                    ->options([
                        'erangle' => 'Erangle',
                        'miramar' => 'Miramar',
                        'sanhok' => 'Sanhok',
                        'rondo' => 'Rondo',
                        'vikendi' => 'Vikendi',
                        'taego' => 'Taego',
                        'deston' => 'Deston',
                        'karakin' => 'Karakin',
                        'paramo' => 'Paramo',
                        'haven' => 'Haven',
                    ])
                    ->default('erangle')
                    ->required()
                    ->disabled(),
                // Toggle::make('is_active')
                //     ->label('Active Match')
                //     ->default(false)
                //     ->inline(false)
                //     ->disabled(),
                // Toggle::make('is_completed')
                //     ->label('Is Completed')
                //     ->default(false)
                //     ->inline(false)
                //     ->live()
                //     ->disabled(fn($record) => $record && !$record->matchStats()->where('is_winner', true)->exists())
                //     ->hint(fn($record) => $record && !$record->matchStats()->where('is_winner', true)->exists() ? 'Please declare a winner in match stats first.' : null)
                //     ->hintColor('danger'),
            ])->columns(4);
    }
}
