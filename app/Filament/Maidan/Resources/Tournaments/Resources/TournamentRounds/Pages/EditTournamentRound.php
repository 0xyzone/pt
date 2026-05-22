<?php

namespace App\Filament\Maidan\Resources\Tournaments\Resources\TournamentRounds\Pages;

use App\Filament\Maidan\Resources\Tournaments\Resources\TournamentRounds\TournamentRoundResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditTournamentRound extends EditRecord
{
    protected static string $resource = TournamentRoundResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
