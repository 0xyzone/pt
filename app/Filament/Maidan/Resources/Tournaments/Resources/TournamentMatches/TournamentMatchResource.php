<?php

namespace App\Filament\Maidan\Resources\Tournaments\Resources\TournamentMatches;

use App\Filament\Maidan\Resources\Tournaments\Resources\TournamentMatches\Pages\ListTournamentMatches;
use App\Filament\Maidan\Resources\Tournaments\Resources\TournamentMatches\Pages\CreateTournamentMatch;
use App\Filament\Maidan\Resources\Tournaments\Resources\TournamentMatches\Pages\EditTournamentMatch;
use App\Filament\Maidan\Resources\Tournaments\Resources\TournamentMatches\Resources\MatchStats\RelationManagers\MatchStatsRelationManager;
use App\Filament\Maidan\Resources\Tournaments\Resources\TournamentMatches\Schemas\TournamentMatchForm;
use App\Filament\Maidan\Resources\Tournaments\Resources\TournamentMatches\Tables\TournamentMatchesTable;
use App\Filament\Maidan\Resources\Tournaments\TournamentResource;
use App\Models\TournamentMatch;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class TournamentMatchResource extends Resource
{
    protected static ?string $model = TournamentMatch::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static ?string $parentResource = TournamentResource::class;

    protected static ?string $recordTitleAttribute = 'name';

    public static function form(Schema $schema): Schema
    {
        return TournamentMatchForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return TournamentMatchesTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            'matchStats' => MatchStatsRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'create' => CreateTournamentMatch::route('/create'),
            'edit' => EditTournamentMatch::route('/{record}/edit'),
            'index' => ListTournamentMatches::route('/'),
        ];
    }
}
