<?php

namespace App\Filament\Maidan\Resources\Tournaments\Resources\TournamentTeams\Pages;

use App\Filament\Maidan\Resources\Tournaments\Resources\TournamentTeams\TournamentTeamResource;
use App\Filament\Maidan\Resources\Tournaments\TournamentResource;
use App\Models\TournamentTeam;
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
