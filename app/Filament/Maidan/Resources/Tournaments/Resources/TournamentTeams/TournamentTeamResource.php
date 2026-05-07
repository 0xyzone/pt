<?php

namespace App\Filament\Maidan\Resources\Tournaments\Resources\TournamentTeams;

use App\Filament\Maidan\Resources\Tournaments\Pages\ListTournamentTeams;
use App\Filament\Maidan\Resources\Tournaments\Resources\TournamentTeams\Pages\CreateTournamentTeam;
use App\Filament\Maidan\Resources\Tournaments\Resources\TournamentTeams\Pages\EditTournamentTeam;
use App\Filament\Maidan\Resources\Tournaments\Resources\TournamentTeams\Schemas\TournamentTeamForm;
use App\Filament\Maidan\Resources\Tournaments\Resources\TournamentTeams\Tables\TournamentTeamsTable;
use App\Filament\Maidan\Resources\Tournaments\TournamentResource;
use App\Models\TournamentTeam;
use BackedEnum;
use Filament\Pages\Page;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class TournamentTeamResource extends Resource
{
    protected static ?string $model = TournamentTeam::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static ?string $parentResource = TournamentResource::class;

    protected static ?string $recordTitleAttribute = 'name';

    public static function form(Schema $schema): Schema
    {
        return TournamentTeamForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return TournamentTeamsTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'create' => CreateTournamentTeam::route('/create'),
            'edit' => EditTournamentTeam::route('/{record}/edit'),
            'index' => ListTournamentTeams::route('/'),
        ];
    }
    public static function getRecordSubNavigation(Page $page): array
    {
        return $page->generateNavigationItems([
            ListTournamentTeams::class,
            EditTournamentTeam::class,
            CreateTournamentTeam::class,
        ]);
    }
}
