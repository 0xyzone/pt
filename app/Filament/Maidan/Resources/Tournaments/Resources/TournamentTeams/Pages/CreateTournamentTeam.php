<?php

namespace App\Filament\Maidan\Resources\Tournaments\Resources\TournamentTeams\Pages;

use App\Filament\Maidan\Resources\Tournaments\Resources\TournamentTeams\TournamentTeamResource;
use App\Filament\Maidan\Resources\Tournaments\TournamentResource;
use BackedEnum;
use Filament\Resources\Pages\CreateRecord;
use Filament\Support\Icons\Heroicon;

class CreateTournamentTeam extends CreateRecord
{
    protected static string $resource = TournamentTeamResource::class;
    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedPlusCircle;
    protected static ?string $navigationLabel = 'Add Teams';
    protected ?string $heading = 'Create team for tournament';

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
