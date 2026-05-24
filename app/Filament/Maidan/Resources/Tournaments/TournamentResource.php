<?php

namespace App\Filament\Maidan\Resources\Tournaments;

use App\Filament\Maidan\Resources\Tournaments\Pages\CreateTournament;
use App\Filament\Maidan\Resources\Tournaments\Pages\EditTournament;
use App\Filament\Maidan\Resources\Tournaments\Pages\EditTournamentSetting;
use App\Filament\Maidan\Resources\Tournaments\Resources\TournamentMatches\Pages\ListTournamentMatches;
use App\Filament\Maidan\Resources\Tournaments\Pages\ListTournaments;
use App\Filament\Maidan\Resources\Tournaments\Resources\TournamentTeams\Pages\ListTournamentTeams;
use App\Filament\Maidan\Resources\Tournaments\Resources\TournamentRounds\Pages\ListTournamentRounds;
use App\Filament\Maidan\Resources\Tournaments\Pages\ViewTournament;
use App\Filament\Maidan\Resources\Tournaments\Resources\TournamentMatches\RelationManagers\TournamentMatchesRelationManager;
use App\Filament\Maidan\Resources\Tournaments\Resources\TournamentMatches\TournamentMatchResource;
use App\Filament\Maidan\Resources\Tournaments\Resources\TournamentTeams\Pages\CreateTournamentTeam;
use App\Filament\Maidan\Resources\Tournaments\Resources\TournamentTeams\RelationManagers\TournamentTeamsRelationManager;
use App\Filament\Maidan\Resources\Tournaments\Resources\TournamentSponsors\RelationManagers\TournamentSponsorsRelationManager;
use App\Filament\Maidan\Resources\Tournaments\Resources\TournamentCasters\RelationManagers\TournamentCastersRelationManager;
use App\Filament\Maidan\Resources\Tournaments\Schemas\TournamentForm;
use App\Filament\Maidan\Resources\Tournaments\Tables\TournamentsTable;
use App\Models\Tournament;
use BackedEnum;
use Filament\Pages\Page;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class TournamentResource extends Resource
{
    protected static ?string $model = Tournament::class;
    // protected static ?string $relatedResource = TournamentTeamResource::class;
    protected static ?string $relatedResource = TournamentMatchResource::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedTrophy;
    protected static string|BackedEnum|null $activeNavigationIcon = Heroicon::Trophy;

    protected static ?int $navigationSort = 1;

    protected static ?string $recordTitleAttribute = 'name';

    public static function form(Schema $schema): Schema
    {
        return TournamentForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return TournamentsTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            'tournament_teams' => TournamentTeamsRelationManager::class,
            'tournament_matches' => TournamentMatchesRelationManager::class,
            'tournament_sponsors' => TournamentSponsorsRelationManager::class,
            'casters' => TournamentCastersRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListTournaments::route('/'),
            'create' => CreateTournament::route('/create'),
            // 'view' => ViewTournament::route('/{record}'),
            'edit' => EditTournament::route('/{record}/edit'),
            'rounds' => ListTournamentRounds::route('/{record}/rounds'),
            'settings' => EditTournamentSetting::route('/{record}/settings'),
        ];
    }
    public static function getRecordSubNavigation(Page $page): array
    {
        return $page->generateNavigationItems([
            // ViewTournament::class,
            EditTournament::class,
            EditTournamentSetting::class,
            ListTournamentTeams::class,
            ListTournamentRounds::class,
            ListTournamentMatches::class,
        ]);
    }
}
