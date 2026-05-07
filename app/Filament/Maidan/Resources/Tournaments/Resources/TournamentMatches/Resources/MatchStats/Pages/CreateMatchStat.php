<?php

namespace App\Filament\Maidan\Resources\Tournaments\Resources\TournamentMatches\Resources\MatchStats\Pages;

use App\Filament\Maidan\Resources\Tournaments\Resources\TournamentMatches\Resources\MatchStats\MatchStatResource;
use Filament\Resources\Pages\CreateRecord;

class CreateMatchStat extends CreateRecord
{
    protected static string $resource = MatchStatResource::class;
}
