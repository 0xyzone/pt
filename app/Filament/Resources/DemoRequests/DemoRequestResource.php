<?php

namespace App\Filament\Resources\DemoRequests;

use App\Filament\Resources\DemoRequests\Pages\EditDemoRequest;
use App\Filament\Resources\DemoRequests\Pages\ListDemoRequests;
use App\Models\DemoRequest;
use BackedEnum;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use UnitEnum;

class DemoRequestResource extends Resource
{
    protected static ?string $model = DemoRequest::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedEnvelope;

    protected static ?string $navigationLabel = 'Demo Requests';

    protected static string|UnitEnum|null $navigationGroup = 'Lead Management';

    protected static ?int $navigationSort = 1;

    protected static ?string $recordTitleAttribute = 'name';

    public static function getNavigationBadge(): ?string
    {
        return (string) DemoRequest::where('status', 'new')->count() ?: null;
    }

    public static function getNavigationBadgeColor(): ?string
    {
        return 'warning';
    }

    public static function form(Schema $schema): Schema
    {
        return $schema->components([
            Section::make('Contact Information')
                ->columns(2)
                ->schema([
                    TextInput::make('name')
                        ->required()->maxLength(100),
                    TextInput::make('email')
                        ->email()->required()->maxLength(150),
                    TextInput::make('organization')
                        ->maxLength(150),
                    TextInput::make('phone')
                        ->maxLength(20),
                ]),
            Section::make('Request Details')
                ->schema([
                    Textarea::make('message')
                        ->rows(3)->maxLength(1000)->columnSpanFull(),
                ]),
            Section::make('Admin')
                ->columns(2)
                ->schema([
                    Select::make('status')
                        ->options(DemoRequest::STATUSES)
                        ->required(),
                    TextInput::make('ip_address')
                        ->disabled()->dehydrated(false),
                    Textarea::make('admin_notes')
                        ->rows(3)->columnSpanFull()
                        ->placeholder('Internal notes (not visible to the requester)'),
                ]),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->defaultSort('created_at', 'desc')
            ->columns([
                TextColumn::make('name')
                    ->searchable()->sortable()->weight('bold'),
                TextColumn::make('email')
                    ->searchable()->copyable()
                    ->icon('heroicon-o-envelope'),
                TextColumn::make('organization')
                    ->searchable()->placeholder('—'),
                TextColumn::make('phone')
                    ->placeholder('—'),
                TextColumn::make('status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'new'       => 'warning',
                        'contacted' => 'info',
                        'converted' => 'success',
                        'rejected'  => 'danger',
                        default     => 'gray',
                    })
                    ->formatStateUsing(fn ($state) => DemoRequest::STATUSES[$state] ?? ucfirst($state)),
                TextColumn::make('created_at')
                    ->label('Submitted')
                    ->dateTime('d M Y, h:i A')
                    ->sortable(),
            ])
            ->filters([
                SelectFilter::make('status')
                    ->options(DemoRequest::STATUSES),
            ])
            ->actions([
                EditAction::make()->label('Manage'),
            ])
            ->bulkActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => ListDemoRequests::route('/'),
            'edit'  => EditDemoRequest::route('/{record}/edit'),
        ];
    }
}
