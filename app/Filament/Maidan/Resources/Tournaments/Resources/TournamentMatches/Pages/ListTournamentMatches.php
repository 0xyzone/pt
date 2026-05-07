<?php

namespace App\Filament\Maidan\Resources\Tournaments\Pages;

use App\Filament\Maidan\Resources\Tournaments\Resources\TournamentMatches\TournamentMatchResource;
use BackedEnum;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;
use Filament\Support\Icons\Heroicon;

class ListTournamentMatches extends ListRecords
{
    protected static string $resource = TournamentMatchResource::class;
    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedPuzzlePiece;
    protected static ?string $navigationLabel = 'Manage Games';

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
