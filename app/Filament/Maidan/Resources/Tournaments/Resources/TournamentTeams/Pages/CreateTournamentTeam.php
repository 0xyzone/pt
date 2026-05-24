<?php

namespace App\Filament\Maidan\Resources\Tournaments\Resources\TournamentTeams\Pages;

use App\Filament\Maidan\Resources\Tournaments\Resources\TournamentTeams\TournamentTeamResource;
use App\Filament\Maidan\Resources\Tournaments\TournamentResource;
use App\Models\TournamentTeam;
use BackedEnum;
use Filament\Resources\Pages\CreateRecord;
use Filament\Support\Icons\Heroicon;

class CreateTournamentTeam extends CreateRecord
{
    protected static string $resource = TournamentTeamResource::class;
    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedPlusCircle;
    protected static ?string $navigationLabel = 'Add Teams';
    protected ?string $heading = 'Create team for tournament';



    public static function getNavigationBadge(): ?string
    {
        // Show a warning badge if this tournament has no settings configured yet.
        // We read the tournamentId from the current route parameter.
        $tournamentId = request()->route('record');
        if (!$tournamentId) {
            return null;
        }
        $exists = TournamentTeam::where('tournament_id', $tournamentId)->exists();
        return $exists ? null : '!';
    }

    public static function getNavigationBadgeColor(): ?string
    {
        $tournamentId = request()->route('record');
        if (!$tournamentId) {
            return null;
        }
        $exists = TournamentTeam::where('tournament_id', $tournamentId)->exists();
        return $exists ? null : 'warning';
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
