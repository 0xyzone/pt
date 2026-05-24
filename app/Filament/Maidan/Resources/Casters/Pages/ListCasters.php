<?php

namespace App\Filament\Maidan\Resources\Casters\Pages;

use App\Filament\Maidan\Resources\Casters\CasterResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListCasters extends ListRecords
{
    protected static string $resource = CasterResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
