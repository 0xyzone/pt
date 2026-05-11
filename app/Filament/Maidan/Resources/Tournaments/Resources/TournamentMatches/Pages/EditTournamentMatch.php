<?php

namespace App\Filament\Maidan\Resources\Tournaments\Resources\TournamentMatches\Pages;

use App\Filament\Maidan\Resources\Tournaments\Resources\TournamentMatches\TournamentMatchResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditTournamentMatch extends EditRecord
{
    protected static string $resource = TournamentMatchResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }

    protected function afterSave(): void
    {
        broadcast(new \App\Events\TournamentMatchUpdated($this->record));
    }

    protected function getListeners(): array
    {
        return [
            'refreshTournamentMatchForm' => '$refresh',
        ];
    }
}
