<?php

namespace App\Filament\Maidan\Resources\Tournaments\Resources\TournamentMatches\Resources\MatchStats\RelationManagers;

use App\Filament\Maidan\Resources\Tournaments\Resources\TournamentMatches\Resources\MatchStats\MatchStatResource;
use Filament\Actions\CreateAction;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables\Table;

class MatchStatsRelationManager extends RelationManager
{
    protected static string $relationship = 'matchStats';

    protected static ?string $relatedResource = MatchStatResource::class;

    public function table(Table $table): Table
    {
        return $table
            ->headerActions([
                CreateAction::make(),
            ]);
    }
}
