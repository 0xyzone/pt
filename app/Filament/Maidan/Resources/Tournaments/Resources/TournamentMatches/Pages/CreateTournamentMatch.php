<?php

namespace App\Filament\Maidan\Resources\Tournaments\Resources\TournamentMatches\Pages;

use App\Filament\Maidan\Resources\Tournaments\Resources\TournamentMatches\TournamentMatchResource;
use App\Filament\Maidan\Resources\Tournaments\TournamentResource;
use Filament\Resources\Pages\CreateRecord;

class CreateTournamentMatch extends CreateRecord
{
    protected static string $resource = TournamentMatchResource::class;

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
