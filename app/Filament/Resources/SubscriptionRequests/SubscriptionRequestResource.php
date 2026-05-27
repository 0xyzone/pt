<?php

namespace App\Filament\Resources\SubscriptionRequests;

use App\Filament\Resources\SubscriptionRequests\Pages\EditSubscriptionRequest;
use App\Filament\Resources\SubscriptionRequests\Pages\ListSubscriptionRequests;
use App\Filament\Resources\SubscriptionRequests\Schemas\SubscriptionRequestForm;
use App\Filament\Resources\SubscriptionRequests\Tables\SubscriptionRequestsTable;
use App\Models\SubscriptionRequest;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class SubscriptionRequestResource extends Resource
{
    protected static ?string $model = SubscriptionRequest::class;

    protected static ?string $navigationLabel = 'Subscription Requests';

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedInboxArrowDown;

    protected static string|\UnitEnum|null $navigationGroup = 'SaaS Management';

    protected static ?int $navigationSort = 3;

    public static function form(Schema $schema): Schema
    {
        return SubscriptionRequestForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return SubscriptionRequestsTable::configure($table);
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
            'index' => ListSubscriptionRequests::route('/'),
            'edit' => EditSubscriptionRequest::route('/{record}/edit'),
        ];
    }
}
