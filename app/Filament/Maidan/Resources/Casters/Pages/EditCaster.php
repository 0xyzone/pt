<?php

namespace App\Filament\Maidan\Resources\Casters\Pages;

use App\Filament\Maidan\Resources\Casters\CasterResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditCaster extends EditRecord
{
    protected static string $resource = CasterResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
