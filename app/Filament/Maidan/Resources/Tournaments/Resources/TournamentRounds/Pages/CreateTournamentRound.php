<?php

namespace App\Filament\Maidan\Resources\Tournaments\Resources\TournamentRounds\Pages;

use App\Filament\Maidan\Resources\Tournaments\Resources\TournamentRounds\TournamentRoundResource;
use BackedEnum;
use Filament\Resources\Pages\CreateRecord;
use Filament\Support\Icons\Heroicon;

class CreateTournamentRound extends CreateRecord
{
    protected static string $resource = TournamentRoundResource::class;
    
    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedPlusCircle;
    
    protected static ?string $navigationLabel = 'Add Round';
    
    protected ?string $heading = 'Create round for tournament';
}
