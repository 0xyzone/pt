<?php

namespace App\Filament\Maidan\Resources\Tournaments\Resources\TournamentRounds;

use App\Filament\Maidan\Resources\Tournaments\Resources\TournamentRounds\Pages\ListTournamentRounds;
use App\Filament\Maidan\Resources\Tournaments\Resources\TournamentRounds\Pages\CreateTournamentRound;
use App\Filament\Maidan\Resources\Tournaments\Resources\TournamentRounds\Pages\EditTournamentRound;
use App\Filament\Maidan\Resources\Tournaments\Resources\TournamentRounds\Schemas\TournamentRoundForm;
use App\Filament\Maidan\Resources\Tournaments\Resources\TournamentRounds\Tables\TournamentRoundsTable;
use App\Filament\Maidan\Resources\Tournaments\TournamentResource;
use App\Models\TournamentRound;
use BackedEnum;
use Filament\Pages\Page;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class TournamentRoundResource extends Resource
{
    protected static ?string $model = TournamentRound::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedFlag;

    protected static ?string $parentResource = TournamentResource::class;

    protected static ?string $recordTitleAttribute = 'name';
    protected static ?int $navigationSort = 2;

    public static function form(Schema $schema): Schema
    {
        return TournamentRoundForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return TournamentRoundsTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            'tournamentMatches' => \App\Filament\Maidan\Resources\Tournaments\Resources\TournamentRounds\RelationManagers\RoundMatchesRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'create' => CreateTournamentRound::route('/create'),
            'edit' => EditTournamentRound::route('/{record}/edit'),
            'index' => ListTournamentRounds::route('/'),
        ];
    }

    public static function getRecordSubNavigation(Page $page): array
    {
        return $page->generateNavigationItems([
            ListTournamentRounds::class,
            EditTournamentRound::class,
            CreateTournamentRound::class,
        ]);
    }
}
