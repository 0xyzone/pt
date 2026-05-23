<?php

namespace App\Filament\Maidan\Resources\Tournaments\Resources\TournamentMatches\Pages;

use App\Filament\Maidan\Resources\Tournaments\Resources\TournamentMatches\TournamentMatchResource;
use App\Filament\Maidan\Resources\Tournaments\TournamentResource;
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

    public function getSubNavigation(): array
    {
        return TournamentResource::getRecordSubNavigation($this);
    }

    public function getSubNavigationParameters(): array
    {
        return [
            'record' => $this->getParentRecord(),
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
