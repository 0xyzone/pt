<?php

namespace App\Filament\Maidan\Resources\Tournaments\Resources\TournamentMatches\Resources\MatchStats;

use App\Filament\Maidan\Resources\Tournaments\Resources\TournamentMatches\Resources\MatchStats\Pages\CreateMatchStat;
use App\Filament\Maidan\Resources\Tournaments\Resources\TournamentMatches\Resources\MatchStats\Pages\EditMatchStat;
use App\Filament\Maidan\Resources\Tournaments\Resources\TournamentMatches\Resources\MatchStats\Schemas\MatchStatForm;
use App\Filament\Maidan\Resources\Tournaments\Resources\TournamentMatches\Resources\MatchStats\Tables\MatchStatsTable;
use App\Filament\Maidan\Resources\Tournaments\Resources\TournamentMatches\TournamentMatchResource;
use App\Models\MatchStat;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class MatchStatResource extends Resource
{
    protected static ?string $model = MatchStat::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static ?string $parentResource = TournamentMatchResource::class;

    public static function form(Schema $schema): Schema
    {
        return MatchStatForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return MatchStatsTable::configure($table);
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
            'create' => CreateMatchStat::route('/create'),
            'edit' => EditMatchStat::route('/{record}/edit'),
        ];
    }
}
