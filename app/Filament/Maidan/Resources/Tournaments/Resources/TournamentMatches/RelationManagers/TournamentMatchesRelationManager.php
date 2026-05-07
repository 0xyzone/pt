<?php

namespace App\Filament\Maidan\Resources\Tournaments\Resources\TournamentMatches\RelationManagers;

use App\Filament\Maidan\Resources\Tournaments\Resources\TournamentMatches\TournamentMatchResource;
use Filament\Actions\CreateAction;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables\Table;

class TournamentMatchesRelationManager extends RelationManager
{
    protected static string $relationship = 'tournamentMatches';

    protected static ?string $relatedResource = TournamentMatchResource::class;

    public function table(Table $table): Table
    {
        return $table
            ->headerActions([
                CreateAction::make(),
            ]);
    }
}
