<?php

namespace App\Filament\Maidan\Resources\Casters\Schemas;

use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;
use Illuminate\Support\Facades\Auth;

class CasterForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Hidden::make('user_id')
                ->default(Auth::id()),
                TextInput::make('display_name'),
                TextInput::make('display_handle'),
                TextInput::make('vdoninja_link')
                    ->label('VDO.Ninja View Link')
                    ->url()
                    ->nullable()
                    ->placeholder('e.g., https://vdo.ninja/?v=roomname'),
                FileUpload::make('image')
                    ->image()
                    ->directory('casters')
                    ->disk('public')
                    ->maxSize(8*1024),
            ]);
    }
}
