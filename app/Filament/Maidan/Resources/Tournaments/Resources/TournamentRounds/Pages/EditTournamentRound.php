<?php

namespace App\Filament\Maidan\Resources\Tournaments\Resources\TournamentRounds\Pages;

use App\Filament\Maidan\Resources\Tournaments\Resources\TournamentRounds\TournamentRoundResource;
use App\Filament\Maidan\Resources\Tournaments\TournamentResource;
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
