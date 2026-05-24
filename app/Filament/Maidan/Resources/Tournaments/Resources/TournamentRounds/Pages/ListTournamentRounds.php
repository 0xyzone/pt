<?php

namespace App\Filament\Maidan\Resources\Tournaments\Resources\TournamentRounds\Pages;

use App\Filament\Maidan\Resources\Tournaments\Resources\TournamentRounds\TournamentRoundResource;
use App\Filament\Maidan\Resources\Tournaments\TournamentResource;
use App\Models\TournamentRound;
use BackedEnum;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;
use Filament\Support\Icons\Heroicon;

class ListTournamentRounds extends ListRecords
{
    protected static string $resource = TournamentRoundResource::class;
    
    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedFlag;
    
    protected static ?string $navigationLabel = 'Manage Rounds';
    protected static ?int $navigationSort = 5;

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
        $exists = TournamentRound::where('tournament_id', $tournamentId)->exists();
        return $exists ? null : '!';
    }

    public static function getNavigationBadgeColor(): ?string
    {
        $tournamentId = self::getTournamentId();
        if (!$tournamentId) {
            return null;
        }
        $exists = TournamentRound::where('tournament_id', $tournamentId)->exists();
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

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
