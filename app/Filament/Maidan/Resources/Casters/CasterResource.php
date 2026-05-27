<?php

namespace App\Filament\Maidan\Resources\Casters;

use App\Filament\Maidan\Resources\Casters\Pages\CreateCaster;
use App\Filament\Maidan\Resources\Casters\Pages\EditCaster;
use App\Filament\Maidan\Resources\Casters\Pages\ListCasters;
use App\Filament\Maidan\Resources\Casters\Schemas\CasterForm;
use App\Filament\Maidan\Resources\Casters\Tables\CastersTable;
use App\Models\Caster;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class CasterResource extends Resource
{
    protected static ?string $model = Caster::class;

    public static function canViewAny(): bool
    {
        return \Illuminate\Support\Facades\Auth::check() && \App\Services\SubscriptionService::can(\Illuminate\Support\Facades\Auth::user(), 'casters_management');
    }

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedMicrophone;
    protected static string|BackedEnum|null $activeNavigationIcon = Heroicon::Microphone;

    protected static ?int $navigationSort = 2;

    protected static ?string $recordTitleAttribute = 'name';

    public static function form(Schema $schema): Schema
    {
        return CasterForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return CastersTable::configure($table);
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
            'index' => ListCasters::route('/'),
            'create' => CreateCaster::route('/create'),
            'edit' => EditCaster::route('/{record}/edit'),
        ];
    }
}
