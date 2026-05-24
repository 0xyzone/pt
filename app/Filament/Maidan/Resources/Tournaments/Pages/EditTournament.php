<?php

namespace App\Filament\Maidan\Resources\Tournaments\Pages;

use App\Filament\Maidan\Resources\Tournaments\TournamentResource;
use App\Models\Tournament;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditTournament extends EditRecord
{
    protected static string $resource = TournamentResource::class;
    protected static ?int $navigationSort = 2;

    protected static function getTournamentId(): ?string
    {
        $tournament = request()->route('tournament') ?? request()->route('record');
        
        return $tournament instanceof \Illuminate\Database\Eloquent\Model ? $tournament->getKey() : $tournament;
    }

    public static function getNavigationBadge(): ?string
    {
        $tournamentId = self::getTournamentId();
        if (!$tournamentId) {
            return null;
        }
        $tournament = Tournament::find($tournamentId);
        if (!$tournament) {
            return null;
        }
        // Exclamation mark if start date, end date, logo image, or banner image is missing
        $isComplete = $tournament->start_date && $tournament->end_date && $tournament->logo_image && $tournament->banner_image;
        return $isComplete ? null : '!';
    }

    public static function getNavigationBadgeColor(): ?string
    {
        $tournamentId = self::getTournamentId();
        if (!$tournamentId) {
            return null;
        }
        $tournament = Tournament::find($tournamentId);
        if (!$tournament) {
            return null;
        }
        $isComplete = $tournament->start_date && $tournament->end_date && $tournament->logo_image && $tournament->banner_image;
        return $isComplete ? null : 'warning';
    }

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
