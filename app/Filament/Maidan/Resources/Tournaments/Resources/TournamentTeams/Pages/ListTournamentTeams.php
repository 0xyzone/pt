<?php

namespace App\Filament\Maidan\Resources\Tournaments\Pages;

use App\Filament\Maidan\Resources\Tournaments\Resources\TournamentTeams\TournamentTeamResource;
use App\Models\TournamentTeam;
use BackedEnum;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;
use Filament\Support\Icons\Heroicon;
use Waad\FilamentImportWizard\Actions\ImportWizardAction;

class ListTournamentTeams extends ListRecords
{
    protected static string $resource = TournamentTeamResource::class;
    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedUsers;
    protected static ?string $navigationLabel = 'Manage Teams';
    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
            ImportWizardAction::make('import')
                ->label('Import Teams')
                ->forModel(TournamentTeam::class)
        ];
    }
}
