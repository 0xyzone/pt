<?php

namespace App\Filament\Maidan\Resources\Tournaments\Pages;

use App\Filament\Maidan\Resources\Tournaments\Resources\TournamentRounds\TournamentRoundResource;
use BackedEnum;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;
use Filament\Support\Icons\Heroicon;

class ListTournamentRounds extends ListRecords
{
    protected static string $resource = TournamentRoundResource::class;
    
    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedFlag;
    
    protected static ?string $navigationLabel = 'Manage Rounds';

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
