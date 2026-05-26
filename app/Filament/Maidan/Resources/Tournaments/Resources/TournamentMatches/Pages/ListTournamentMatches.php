<?php

namespace App\Filament\Maidan\Resources\Tournaments\Resources\TournamentMatches\Pages;

use App\Filament\Maidan\Resources\Tournaments\Resources\TournamentMatches\TournamentMatchResource;
use App\Filament\Maidan\Resources\Tournaments\TournamentResource;
use App\Models\TournamentMatch;
use BackedEnum;
use Filament\Actions\Action;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;
use Filament\Schemas\Components\Tabs\Tab;
use Filament\Support\Icons\Heroicon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;

class ListTournamentMatches extends ListRecords
{
    protected static string $resource = TournamentMatchResource::class;
    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedPuzzlePiece;
    protected static ?string $navigationLabel = 'Manage Games';
    protected static ?int $navigationSort = 6;

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
        $exists = TournamentMatch::where('tournament_id', $tournamentId)->exists();
        return $exists ? null : '!';
    }

    public static function getNavigationBadgeColor(): ?string
    {
        $tournamentId = self::getTournamentId();
        if (!$tournamentId) {
            return null;
        }
        $exists = TournamentMatch::where('tournament_id', $tournamentId)->exists();
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
            Action::make('Overall Ranking')
                ->url(fn() => route('screens.overallranking', ['user_id' => Auth::id()]))
                ->openUrlInNewTab()
                ->visible(fn() => \App\Models\Tournament::where('user_id', Auth::id())->where('is_active', true)->exists())
                ->color('info')
        ];
    }

    public function getTabs(): array
    {
        $tournament = $this->getParentRecord();
        
        $tabs = [
            'all' => Tab::make('All Matches'),
        ];
        
        if ($tournament) {
            $rounds = $tournament->tournamentRounds()->orderBy('id')->get();
            foreach ($rounds as $round) {
                $tabKey = 'round_' . $round->id;
                $tabs[$tabKey] = Tab::make($round->name)
                    ->modifyQueryUsing(fn(Builder $query) => $query->where('tournament_round_id', $round->id));
            }
        }
        
        return $tabs;
    }
}
