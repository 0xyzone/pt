<?php

namespace App\Filament\Maidan\Resources\Tournaments\Resources\TournamentTeams\Pages;

use App\Filament\Maidan\Resources\Tournaments\Resources\TournamentTeams\TournamentTeamResource;
use App\Filament\Maidan\Resources\Tournaments\TournamentResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditTournamentTeam extends EditRecord
{
    protected static string $resource = TournamentTeamResource::class;

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
}
