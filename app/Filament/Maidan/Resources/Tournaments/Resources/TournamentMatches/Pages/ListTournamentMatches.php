<?php

namespace App\Filament\Maidan\Resources\Tournaments\Pages;

use App\Filament\Maidan\Resources\Tournaments\Resources\TournamentMatches\TournamentMatchResource;
use App\Filament\Maidan\Resources\Tournaments\TournamentResource;
use BackedEnum;
use Filament\Actions\Action;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;
use Filament\Support\Icons\Heroicon;

class ListTournamentMatches extends ListRecords
{
    protected static string $resource = TournamentMatchResource::class;
    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedPuzzlePiece;
    protected static ?string $navigationLabel = 'Manage Games';
    protected static ?int $navigationSort = 6;

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
                ->url(fn() => route('screens.overallranking', ['user_id' => auth()->id()]))
                ->openUrlInNewTab()
                ->visible(fn() => \App\Models\Tournament::where('user_id', auth()->id())->where('is_active', true)->exists())
                ->color('info')
        ];
    }
}
